<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * 3 domains:
 * - admin
 * - member
 * - site (public)
 */

require_once('api/member.php');
require_once('api/admin.php');
