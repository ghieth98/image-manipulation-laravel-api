# Image Manipulation API

Welcome to the Image Manipulation API repository! This project is a Laravel-based API designed to provide image resizing functionalities. Users can upload images and resize them to specified dimensions through a RESTful API.

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## Introduction

The Image Manipulation API is built with Laravel and focuses on resizing images. It provides a simple interface for users to upload images and specify new dimensions. The API is designed to be efficient and easy to integrate into various applications.

## Features

- **Image Resizing:** Resize images to specified dimensions.
- **Image Upload:** Upload images for resizing.
- **Image Download:** Download the resized image.

## Installation

You can set up the project either using traditional methods or Laravel Sail.

### Traditional Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/image-manipulation-api.git
   cd image-manipulation-api
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   npm run dev
   ```

3. **Copy the `.env.example` file to `.env` and configure your environment variables:**
   ```bash
   cp .env.example .env
   ```

4. **Generate an application key:**
   ```bash
   php artisan key:generate
   ```

5. **Run the migrations (if applicable):**
   ```bash
   php artisan migrate
   ```

6. **Start the development server:**
   ```bash
   php artisan serve
   ```

### Installation Using Laravel Sail

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/image-manipulation-api.git
   cd image-manipulation-api
   ```

2. **Install Sail and Docker dependencies:**
   ```bash
   composer require laravel/sail --dev
   php artisan sail:install
   ```

3. **Build the Sail Docker containers:**
   ```bash
   ./vendor/bin/sail build
   ```

4. **Start the Sail Docker containers:**
   ```bash
   ./vendor/bin/sail up
   ```

5. **Run the migrations (if applicable):**
   ```bash
   ./vendor/bin/sail php artisan migrate
   ```

6. **Access the application in your browser:**
   The application will be available at `http://localhost`.

## Configuration

To configure the application, you need to set up your `.env` file. Key environment variables include:

- **File System Configuration:**
  - `FILESYSTEM_DRIVER`: Set to the desired driver (e.g., `local` for local storage).

Ensure these variables are correctly set in your `.env` file.

## Usage

Once the application is up and running, you can interact with the API using HTTP requests. The base URL for the API will be `http://localhost/api`.

## API Endpoints

### Resize Image

- **Endpoint:** `POST /api/image/resize`
- **Parameters:**
  - `image`: The image file to be resized.
  - `width`: The desired width of the resized image.
  - `height`: The desired height of the resized image.

**Example Request:**
```bash
curl -X POST http://localhost/api/image/resize \
     -F 'image=@path/to/image.jpg' \
     -F 'width=800' \
     -F 'height=600'
```

**Example Response:**
```json
{
  "success": true,
  "message": "Image resized successfully.",
  "resized_image_url": "http://localhost/storage/resized/image.jpg"
}
```

## Testing

To ensure the reliability of the API, the project includes a suite of tests. To run the tests:

1. **Install PHPUnit if not already installed:**
   ```bash
   composer require --dev phpunit/phpunit
   ```

2. **Run the tests:**
   ```bash
   ./vendor/bin/phpunit
   ```

   Or, if using Laravel Sail:
   ```bash
   ./vendor/bin/sail phpunit
   ```

Tests are organized in the `tests` directory and cover various aspects of the image resizing functionality.

## Contributing

Contributions are welcome! If you'd like to contribute to this project, please follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -am 'Add new feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Create a new Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
