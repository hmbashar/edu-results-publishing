# EDU Results Publishing

A powerful WordPress plugin designed to simplify the process of publishing exam results for educational institutions such as schools, colleges, and universities.

[![WordPress Plugin Version](https://img.shields.io/badge/version-1.2.0-blue.svg)](https://github.com/hmbashar/edu-results-publishing)
[![WordPress Compatibility](https://img.shields.io/badge/wordpress-6.9%20tested-brightgreen.svg)](https://wordpress.org/)
[![License](https://img.shields.io/badge/license-GPLv2-orange.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

## Description

This plugin streamlines the entire process of publishing exam results, saving time and effort for educational institutions of all sizes. Whether you're a small school or a large university, this plugin is tailored to meet your needs with an easy-to-use interface that empowers administrators and educators to effortlessly manage and showcase academic achievements.

## Features

### College Information Display
- **College Name**: Displays the name of the college
- **College Registration Number**: Showcases the college's official registration number
- **College Contact Details**: Lists the college's phone number and email address
- **College Address**: Provides the physical address of the college
- **College Website URL**: Directs to the college's official website
- **College Logo**: Features the college's logo
- **Result Page Banner Heading**: Offers a customizable heading for the result page banner

### Student Personal Information Display
- **Student Roll Number and Registration Number**: Displays roll and registration numbers
- **Student's Name**: Retrieved from the post title
- **Parental Details**: Names of the student's parents
- **DOB, Board, and Group Information**: Date of birth, board name, and group
- **Student ID and Result Status**: Unique ID and result status
- **Student Type**: Classification of the student type

### Student's Photo Display
- Capable of displaying the student's photo, either from a post thumbnail or a default image

### Student Academic Information Display
- **Student's GPA**: Displays Grade Point Average (GPA) and GPA without additional subjects
- **Subjects Information**: Lists subjects along with marks, letter grades, and GPA

### Dynamic Subject Result Table
- Generates a table dynamically showing each subject with marks, letter grades, and GPA

## Installation

1. Download the plugin from [GitHub](https://github.com/hmbashar/edu-results-publishing)
2. Upload the plugin files to the `/wp-content/plugins/edu-results-publishing` directory, or install the plugin through the WordPress plugins screen directly
3. Activate the plugin through the 'Plugins' screen in WordPress
4. Start configuring your results publishing system

## How to Use

1. Install and activate the plugin
2. Access the WordPress dashboard
3. **Add Subjects**: First, you need to add subjects
4. **Add Taxonomies**: Add Session, Examinations, Boards, Departments
   - Note: These are optional for students but required for results
5. **Add Students**: Add student information
6. **Add Results**: Publish student results
7. **Display Search Form**: Use the shortcode `[cbedu_search_form]` to display the search form on any page

**Note**: A full-width template isn't required but will provide a better view.

## Shortcode

```
[cbedu_search_form]
```

Use this shortcode to display the result search form on any page or post.

## Requirements

- **WordPress**: 4.7 or higher
- **PHP**: 7.0 or higher
- **Tested up to**: WordPress 6.9

## Screenshots

1. Search form
2. Student fields
3. Subject fields
4. Session, Examinations, Boards, Departments fields
5. Results fields
6. Plugin Settings
7. Student Information on result page
8. Search Form with Result Sheet
9. Result Sheet
10. New Search Form
11. New Result Sheet style
12. New Student fields

## Changelog

### 1.2.0
* Enhanced features and improvements
* Bug fixes and performance optimization
* Updated compatibility with WordPress 6.9

### 1.0.2
* Taxonomy Required for result publishing

### 1.0.1
* Added Subject with Marksheet in ajax search result page

### 1.0
* Initial release with result publishing features

## Frequently Asked Questions

### How do I publish student results?

Follow the steps in the "How to Use" section above. The process involves adding subjects, taxonomies, students, and then publishing results.

### Can I customize the result fields?

Yes, the plugin provides customizable fields for college information, student details, and academic results.

## Video Tutorial

[![Watch the tutorial](https://img.youtube.com/vi/GS50XOJcpvA/0.jpg)](https://www.youtube.com/watch?v=GS50XOJcpvA)

[Watch on YouTube](https://www.youtube.com/watch?v=GS50XOJcpvA)

## Support

If you encounter any issues or have questions:
- [GitHub Issues](https://github.com/hmbashar/edu-results-publishing/issues)
- [Support Forum](https://wordpress.org/support/plugin/edu-results-publishing/)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Author

**MD Abul Bashar**
- Facebook: [hmbashar](https://facebook.com/hmbashar)
- GitHub: [hmbashar](https://github.com/hmbashar)

## Donate

If you find this plugin helpful, consider buying me a coffee!

[![Buy Me A Coffee](https://img.shields.io/badge/Donate-Buy%20Me%20A%20Coffee-yellow.svg)](https://www.buymeacoffee.com/hmbashar)

## License

This plugin is licensed under the GPLv2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Roadmap

- Elementor version (Coming Soon)
- Additional features and improvements (Stay tuned!)

---

Made with ❤️ for educational institutions worldwide.
