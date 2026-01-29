# Installation Guide

## For Users

### Requirements

- PHP 8.4 or higher

### Install via Composer

```bash
composer require djdaca/sorted-linked-list
```

That's it! You can now use the library in your project.

## For Developers

### Requirements

- Docker & Docker Compose
- Git
- Make (optional, but recommended)

### Setup

1. **Clone the repository**

```bash
git clone https://github.com/djdaca/sorted-linked-list.git
cd sorted-linked-list
```

2. **Start Docker environment**

```bash
make start
```

This command will:
- Build the Docker container with PHP 8.4 and all required extensions
- Start the container
- Install Composer dependencies

Or run manually:

```bash
make up      # Start containers
make install # Install dependencies
```

3. **Verify installation**

```bash
make test
```

### Docker Environment

The project includes a complete Docker setup for development:

**What's included:**
- PHP 8.4 CLI (Alpine Linux)
- Xdebug 3.5 (for code coverage)
- Composer
- All required PHP extensions (dom, xml, mbstring, etc.)

**Environment configuration:**

The default configuration in `docker/.env.example` works out of the box. If you need custom settings:

```bash
cp docker/.env.example docker/.env
# Edit docker/.env with your preferences
```

**Available environment variables:**
- `XDEBUG_MODE` - Xdebug mode (default: `develop,coverage`)
- `COMPOSER_CACHE_DIR` - Composer cache directory
- `COMPOSER_PROCESS_TIMEOUT` - Composer process timeout

### Available Commands

#### Make Commands

```bash
# Start development environment
make start          # Build, start containers, and install dependencies

# Container management
make up             # Start containers
make down           # Stop and remove containers
make bash           # Access container shell

# Dependency management
make install        # Install Composer dependencies

# Testing and quality
make test           # Run PHPUnit tests
make test-coverage  # Run tests with coverage report
make ci             # Run all checks (CS Fixer, PHPStan, tests)
```

#### Composer Scripts

Once inside the container (via `make bash`) or using `make` commands:

```bash
# Code style
composer cs         # Check code style
composer cs:fix     # Fix code style issues

# Static analysis
composer stan       # Run PHPStan level 9

# Testing
composer test       # Run tests
composer test:coverage  # Run tests with coverage

# All checks
composer ci         # Run CS Fixer, PHPStan, and tests
```

### Running Tests

**Option 1: Using Make commands (from host machine):**

```bash
# All tests
make test

# Tests with code coverage
make test-coverage

# All quality checks
make ci
```

**Option 2: Inside the container:**

```bash
# First, access container shell from host
make bash

# Now you're inside the container, run composer commands:
composer test
composer test:coverage
composer stan
composer cs
composer ci
```

Expected output:
- 61 tests
- 112 assertions
- 100% code coverage for main classes

### Code Quality

The project follows strict quality standards:

**PHPStan Level 9**
```bash
make bash
composer stan
```

**PHP CS Fixer (PSR-12)**
```bash
# Check
make bash
composer cs

# Auto-fix
composer cs:fix
```

**All checks at once**
```bash
make ci
```

This runs:
1. PHP CS Fixer (check mode)
2. PHPStan analysis
3. PHPUnit tests

### Troubleshooting

**Container won't start**
```bash
# Check Docker is running
docker ps

# Rebuild container
make down
make start
```

**Tests fail**
```bash
# Ensure dependencies are installed
make install

# Clear caches
rm -rf .phpunit.cache .php-cs-fixer.cache
```

**Permission issues**
```bash
# Fix permissions (Linux/Mac)
sudo chown -R $USER:$USER .
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests and checks (`make ci`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

**Before submitting:**
- All tests must pass (`make test`)
- Code coverage should remain at 100%
- PHPStan level 9 must pass (`composer stan`)
- Code must follow PSR-12 (`composer cs`)

## Support

- **Issues**: https://github.com/djdaca/sorted-linked-list/issues
- **Source**: https://github.com/djdaca/sorted-linked-list
