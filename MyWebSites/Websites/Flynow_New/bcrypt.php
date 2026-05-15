<?php
// Bcrypt.php - A simple wrapper for password hashing and verification using bcrypt

/**
 * Hash a password using bcrypt.
 *
 * @param string $password The plain text password to hash.
 * @return string The hashed password.
 */
function bcrypt_hash($password) {
    // Default cost for bcrypt
    $cost = 10; 
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
}

/**
 * Verify a password against a bcrypt hash.
 *
 * @param string $password The plain text password.
 * @param string $hash The bcrypt hash to compare with.
 * @return bool True if the password matches the hash, false otherwise.
 */
function bcrypt_verify($password, $hash) {
    return password_verify($password, $hash);
}
