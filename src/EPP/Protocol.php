<?php

declare(strict_types=1);

namespace DreamHun\EPP;

use Exception;

/**
 * Low-level functions useful for both EPP clients and servers
 * @package Net_EPP
 * @version 0.0.4
 * @author Gavin Brown <gavin.brown@nospam.centralnic.com>
 * @revision $Id: Protocol.php,v 1.4 2011/06/28 09:48:02 gavin Exp $
 */

/**
 * Protocol handling class for EPP (Extensible Provisioning Protocol)
 */
class Protocol
{
    /**
     * Read from a socket without blocking
     *
     * @param resource $socket The socket to read from
     * @param int $length The number of bytes to read
     * @throws Exception On socket read errors
     * @return string The data read from the socket
     */
    private static function _fread_nb($socket, int $length): string
    {
        $result = '';

        // Loop reading and checking info to see if we hit timeout
        $info = stream_get_meta_data($socket);
        $time_start = microtime(true);

        while (!$info['timed_out'] && !feof($socket)) {
            // Try read remaining data from socket
            $buffer = @fread($socket, $length - strlen($result));
            // If the buffer actually contains something then add it to the result
            if ($buffer !== false) {
                $result .= $buffer;
                // If we hit the length we looking for, break
                if (strlen($result) == $length) {
                    break;
                }
            } else {
                // Sleep 0.25s
                usleep(250000);
            }
            // Update metadata
            $info = stream_get_meta_data($socket);
            $time_end = microtime(true);
            if (($time_end - $time_start) > 10) {
                throw new Exception('Timeout while reading from EPP Server');
            }
        }
        // Check for timeout
        if ($info['timed_out']) {
            throw new Exception('Timeout while reading data from socket');
        }

        return $result;
    }

    /**
     * Write to a socket without blocking
     *
     * @param resource $socket The socket to write to
     * @param string $buffer The data to write
     * @param int $length The number of bytes to write
     * @throws Exception On socket write errors
     * @return int The number of bytes written
     */
    private static function _fwrite_nb($socket, string $buffer, int $length): int
    {
        // Loop writing and checking info to see if we hit timeout
        $info = stream_get_meta_data($socket);
        $time_start = microtime(true);

        $pos = 0;
        while (!$info['timed_out'] && !feof($socket)) {
            // Some servers don't like alot of data, so keep it small per chunk
            $wlen = $length - $pos;
            if ($wlen > 1024) {
                $wlen = 1024;
            }
            // Try write remaining data from socket
            $written = @fwrite($socket, substr($buffer, $pos), $wlen);
            // If we read something, bump up the position
            if ($written && $written !== false) {
                $pos += $written;
                // If we hit the length we looking for, break
                if ($pos == $length) {
                    break;
                }
            } else {
                // Sleep 0.25s
                usleep(250000);
            }
            // Update metadata
            $info = stream_get_meta_data($socket);
            $time_end = microtime(true);
            if (($time_end - $time_start) > 10) {
                throw new Exception('Timeout while writing to EPP Server');
            }
        }
        // Check for timeout
        if ($info['timed_out']) {
            throw new Exception('Timeout while writing data to socket');
        }

        return $pos;
    }

    /**
     * Get a frame from the stream
     *
     * @param resource $socket The socket to read from
     * @throws Exception On socket read errors
     * @return string The frame data
     */
    public static function getFrame($socket): string
    {
        // Read header
        $hdr = self::_fread_nb($socket, 4);
        if (strlen($hdr) < 4) {
            throw new Exception(sprintf('Short read of %d bytes from peer', strlen($hdr)));
        }

        // Unpack first 4 bytes which is our length
        $unpacked = unpack('N', $hdr);
        $length = $unpacked[1];
        if ($length < 5) {
            throw new Exception(sprintf('Got a bad frame header length of %d bytes from peer', $length));

        } else {
            $length -= 4; // discard the length of the header itself
            // Read frame
            return self::_fread_nb($socket, $length);
        }
    }

    /**
     * Send a frame to the stream
     *
     * @param resource $socket The socket to write to
     * @param string $xml The XML to send
     * @throws Exception On socket write errors
     * @return bool True on success
     */
    public static function sendFrame($socket, string $xml): bool
    {
        // Grab XML length & add on 4 bytes for the counter
        $length = strlen($xml) + 4;
        $res = self::_fwrite_nb($socket, pack('N', $length) . $xml, $length);
        // Check our write matches
        if ($length != $res) {
            throw new Exception("Short write when sending XML");
        }

        return true;
    }

    /**
     * Pack a string into network-byte order
     *
     * @param string $str The string to pack
     * @return string The packed string
     */
    private static function pack_uint32(string $str): string
    {
        return pack('N', $str);
    }
}
