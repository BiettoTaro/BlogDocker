# Laravel Docker Blog Backend

A robust backend API built with Laravel and containerised using Docker. This project serves as the backend for a blog system, offering RESTful endpoints for managing blog content and user authentication. It is designed to support integration with various frontend clients or to be used as a standalone API

## Table of Contents

- Overview
- Features
- Installation
- Usage

## Overview

This project provides a scalable and maintanable foundation for blog management. Built with Laravel and running in /docker containers, this API simplifies the development and deployment process, ensuring a consistent environment for testing and production. In this is integrates various tools and packages to enhance functionality and security, including Sanctum for API authentication, Breeze for rapid scaffolding, Orion for resource controllers and GraphQl for flexible queying.

## Features

- **User Authentication & API Security:**
With Laravel Sanctum for secure API authentication.

- **Rapid Scaffolding:**
Laravel Breeze is used for quick implementation of authentication and application scaffolding.

- **Resource Management:**
Orion package enhances resource controllers, streamlining Crud operations.

- **GraphQl Support:**
Integrated GraphQl endpoints for more flexible data querying and manipulation.

- **RESTful API Endpoints:**
Full CRUD operations for blog posts and comments.

- **Database:**
The main database is PostgreSQL, ensuring reliability and performance for production data.

- **Email System:**
Built-in email functionality for notifications and user communications.

- **Redis Queue:**
A Redis-based queue system is in place to handle background tasks and improve performance.

- **Docker Integration:**
Local development environment using Docker Compose.

- **Database Migration & Seeding**
Automated setup of database schema along with initial data seeding.

- **Testing:**
Feature tests to maintain high code quality and application stability.