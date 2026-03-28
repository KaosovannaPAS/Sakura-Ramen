<?php
/**
 * Vercel Fallback Entry Point
 * This file ensures the application works even if the Vercel "Root Directory" 
 * is set to the project root instead of /api.
 */

// Include the main API entry point
require_once __DIR__ . '/api/index.php';
