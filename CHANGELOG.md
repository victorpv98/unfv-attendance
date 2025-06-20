# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.008] - 2025-06-20
### Changed
- Automatic deployment of migrations

## [1.1.007] - 2025-06-20
### Added
- Complete Docker containerization with Dockerfile and docker-compose.yml
- Apache configuration for Laravel in Docker environment
- PostgreSQL database integration in Docker setup

### Changed
- Updated .env.example with production environment variables template
- Modified composer.json scripts for optimized production deployment

### Infrastructure
- Docker multi-service setup (Laravel app + PostgreSQL database)

## [1.1.006] - 2025-06-20
### Added
- Add Docker configuration for production deployment
- Configure PostgreSQL database for cloud hosting
- Implement environment variables for Render deployment

## [1.1.005] - 2025-06-10
### Fixed
- Fix bugs
- Change simple-qrcode for scan-barcode

## [1.1.004] - 2025-05-10
### Fixed
- Fix bug dropdown-menu users

## [1.1.003] - 2025-05-08
### Added
- Add EnrollmentController to handle student enrollment
- Implement mobile access instructions in README.md
- Improve StudentController with attendance tracking and semester filtering
- Update ScheduleController to support Spanish weekday names
- Integrate faculty and semester filtering in course listings
- Optimize QR code generation and handling

## [1.1.002] - 2025-05-08
### Changed
- Fix views and routes
- Change Tailwind to Bootstrap

## [1.1.001] - 2025-05-07
### Added
- Add CHANGELOG.md

## [1.0.000] - 2025-05-07
### Added
- New project - UNFV Attendance System
- Initial Laravel application setup
- Basic attendance tracking functionality
- User authentication system
- QR code generation for attendance
- Course and student management
- PostgreSQL database integration