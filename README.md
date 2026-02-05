# 📖 Manga Website : Your Ultimate Online Manga Reading Experience

A dynamic web platform built with PHP, HTML, CSS, and JavaScript, offering a seamless experience for browsing, reading, and interacting with your favorite manga series.

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![License](https://img.shields.io/badge/license-Unlicensed-orange)
![Stars](https://img.shields.io/github/stars/hedi0/manga_siteweb?style=social)
![Forks](https://img.shields.io/github/forks/hedi0/manga_siteweb?style=social)

![Manga Website](/profile_preview.png)

*A glimpse into the manga_siteweb platform.*

## ✨ Features

`manga_siteweb` is designed to provide a rich and interactive manga browsing experience. Here are some of its core features:

*   **📚 Extensive Manga Library:** Browse a wide collection of manga series with detailed information, cover art, and organized chapters.
*   **👤 User Authentication & Profiles:** Securely sign up, log in, and manage your personal profile, including profile picture updates and personal details.
*   **💬 Interactive Review System:** Share your thoughts and engage with the community by leaving reviews on your favorite manga chapters and series.
*   **✉️ Contact & Support:** Easily get in touch with the site administrators through a dedicated contact form for inquiries or support.
*   **🚀 Dynamic Content Loading:** Leverage client-side fetching with `node-fetch` for a smoother, more responsive user experience, especially on pages like the homepage or latest updates.

## 🛠️ Installation Guide

Follow these steps to get `manga_siteweb` up and running on your local machine.

### Prerequisites

Before you begin, ensure you have the following installed:

*   **Web Server:** Apache or Nginx with PHP support.
*   **PHP:** Version 7.4 or higher.
*   **MySQL:** Database server.
*   **Composer:** PHP dependency manager (optional, but good practice).
*   **Node.js & npm:** For `node-fetch` dependency.

### Step-by-Step Installation

1.  **Clone the Repository:**
    First, clone the `manga_siteweb` repository to your local machine:

    ```bash
    git clone https://github.com/hedi0/manga_siteweb.git
    cd manga_siteweb
    ```

2.  **Install Node.js Dependencies:**
    Navigate to the project root and install the JavaScript dependencies, primarily `node-fetch`:

    ```bash
    npm install
    ```

3.  **Database Setup:**
    *   Create a new MySQL database for the project (e.g., `manga_db`).
    *   Import your database schema. A sample schema might be provided in a `database.sql` file (not included in the project structure, so this is a placeholder instruction).
    *   Update `DbConnection.php` with your database credentials:

    ```php
    <?php
    class DbConnection {
        private $host = "localhost";
        private $user = "your_db_user"; // Replace with your MySQL username
        private $password = "your_db_password"; // Replace with your MySQL password
        private $dbname = "manga_db"; // Replace with your database name
        private $conn;

        public function connect() {
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->user, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo "Connection Error: " . $e->getMessage();
            }
            return $this->conn;
        }
    }
    ?>
    ```
    *   Replace `your_db_user` and `your_db_password` with your actual MySQL credentials.

4.  **Web Server Configuration:**
    *   Place the `manga_siteweb` project directory within your web server's document root (e.g., `htdocs` for Apache, `www` for Nginx).
    *   Ensure your web server is configured to serve PHP files.
    *   Access the application via your browser, typically at `http://localhost/manga_siteweb/homePage.html` or `http://localhost/manga_siteweb/`.

## 🚀 Usage Examples

Once installed, navigate to the homepage of the `manga_siteweb` to begin your journey.

### Browsing Manga

The `homePage.html` serves as your entry point, showcasing the latest updates and popular series. You can click on any manga to view its details (`Manga.html`) and then proceed to read individual chapters (`chapters.html`).

```html
<!-- Example of a link to a manga detail page -->
<a href="Manga.html?id=berserk">
    <img src="Berserk.jpg" alt="Berserk Cover">
    <h3>Berserk</h3>
    <p>A dark fantasy epic...</p>
</a>
```

### User Authentication

*   **Sign Up:** Access `signup.php` to create a new account.
*   **Login:** Use `login.php` to access your existing profile.
*   **Profile Management:** After logging in, visit `Profile.php` to view and `EditProfile.php` to modify your profile details and picture.

### Leaving a Review

On a manga's chapter page, you can find a section to submit your reviews. This interaction is handled via `Review.html` (client-side form) and likely processed by a PHP backend.

```html
<!-- Example review form snippet -->
<form action="submit_review.php" method="POST">
    <textarea name="review_text" placeholder="Share your thoughts..."></textarea>
    <input type="number" name="rating" min="1" max="5">
    <button type="submit">Submit Review</button>
</form>
```

### Contacting Support

If you have any questions or need support, the `contact.php` page provides a form to reach out. Submissions are processed by `contact_submit.php`.

![Usage Screenshot]([placeholder-usage-screenshot])
*A placeholder image showcasing the user interface for browsing manga or managing a profile.*

## 🛣️ Project Roadmap

The `manga_siteweb` project is continuously evolving. Here are some planned features and improvements:

*   **Version 1.1.0 - Enhanced User Experience:**
    *   Implement a robust search functionality for manga titles and genres.
    *   Add user-specific reading lists and bookmarks.
    *   Improve responsive design for better mobile compatibility across all pages.
*   **Version 1.2.0 - Community & Moderation:**
    *   Introduce an administrator panel for content management and user moderation.
    *   Allow users to follow manga series and receive update notifications.
    *   Expand review system with voting and commenting features.
*   **Future Goals:**
    *   API integration for external manga sources.
    *   Multi-language support.
    *   Dark mode theme option.

## 🤝 Contribution Guidelinese welcome contributions to `manga_siteweb`! Please follow these guidelines to ensure a smooth collaboration process.
f
##fdbb# Code Styl
fdbc  bcv**PHP: Adhere to PSR-12 codujhuihuiing style guuide.
* **cvvcHTML/CS:** Usegyuggyic HTML5, well-orggfngfanidfzed Centsgyggjlock Elemenifier) methodology where appropriate.
*   **JavacvbScript:** Follow AiraScrjbgiipt Style Guid$$dfd
*   Ensure cbconsistent indentation (4 spaces o2 spaceghfds for HTML/CSS/JS).
### Banch Nivcbcvng ConventiPleae use desgyuvyhjs based oniuguygn the type of chgteture/oatur (eb.g., `feature/uhuhukookmark`
  `b/isjhvjbsue-deption` (e.g., `bugfix/logn-buiu
*   `ref-act (e.gactor/
