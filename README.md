# AutoColor Plugin for DokuWiki

The **AutoColor** plugin for DokuWiki automatically highlights and colors predefined keywords across your wiki pages. Instead of relying on a clunky admin interface, it uses a standard DokuWiki table as its central configuration source.

## ✨ Features

* **Wiki-Based Configuration:** Manage all your keywords, acronyms, and colors intuitively via a simple wiki table.
* **Case-Sensitive:** Differentiates between exact uppercase and lowercase spellings.
* **Lightweight & Fast:** Hooks directly into DokuWiki's native lexer and fully supports the caching system. Zero performance overhead.


## 📦 Installation

### Option 1: Via Extension Manager (Recommended)
1. Download the latest release as a `.zip` file from this repository.
2. Log into your DokuWiki and navigate to **Admin -> Extension Manager**.
3. Go to the **Manual Install** tab.
4. Upload the `.zip` file and click "Install".

### Option 2: Manual Installation
1. Clone this repository or extract the downloaded `.zip` file.
2. Ensure the extracted folder is named exactly `autocolor`.
3. Move the folder into the `lib/plugins/` directory of your DokuWiki installation.

## 🛠️ Usage & Configuration

Once installed, setting up your colors takes less than a minute.

1. Navigate to **Admin -> Configuration Settings** and find the `autocolor` section.
2. Define the wiki page you want to use for your configuration (Default is `admin:autocolor`).
3. Create this exact page in your DokuWiki and insert a table using the following syntax:

```text
====== AutoColor Configuration ======

^ Keywords (comma-separated) ^ Color (Hex code or HTML color name) ^
| DokuWiki, Wiki             | #0055b3                             |
| Error, Warning, Danger     | red                                 |
| Success, Done, Finished    | green                               |
