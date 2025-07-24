<?php

namespace Mkhab7\V2Board\SDK\Contracts;

interface AuthInterface
{
    /**
     * Authenticate user with email and password
     *
     * @param string $email
     * @param string $password
     * @return array Authentication response data
     * @throws \Mkhab7\V2Board\SDK\Exceptions\AuthenticationException
     */
    public function login(string $email, string $password): array;
    
    /**
     * Get current authentication token
     *
     * @return string|null
     */
    public function getToken(): ?string;
    
    /**
     * Check if user is authenticated
     *
     * @return bool
     */
    public function isAuthenticated(): bool;
    
    /**
     * Logout current user
     *
     * @return void
     */
    public function logout(): void;
} 