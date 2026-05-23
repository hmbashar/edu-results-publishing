# User Manual: EDU Results Publishing

This manual explains how a WordPress administrator or educational institution staff member should use the EDU Results Publishing plugin to publish student examination results.

## 1. What This Plugin Does

EDU Results Publishing lets a school, college, university, or training institute manage and publish exam results from inside WordPress.

It provides admin screens for:

- Students
- Subjects
- Results
- Session Years
- Examinations
- Boards
- Departments/Groups
- Institution settings

It also provides a public result search form through a shortcode.

## 2. Requirements

Minimum requirements declared by the plugin:

- WordPress: **5.0 or later**
- PHP: **7.2 or later**
- User role: an administrator or a user with enough permission to create/edit posts and manage plugin settings

Recommended environment:

- Latest stable WordPress version supported by your site.
- A full-width page template for the result search page.
- Pretty permalinks enabled.
- HTTPS enabled, especially if student data is public-facing.

## 3. Installation

1. Log in to your WordPress dashboard.
2. Go to **Plugins > Add New > Upload Plugin**.
3. Upload the plugin ZIP file.
4. Click **Install Now**.
5. Click **Activate Plugin**.
6. After activation, check the WordPress admin menu for **EDU Results**.

## 4. Main Admin Menu

The plugin registers a main admin menu named **EDU Results**. Under it, you should see or access:

- Results
- Students
- Subjects
- Session Years
- Examinations
- Boards
- Departments/Groups
- Settings

The exact WordPress menu layout may depend on admin menu ordering and user permissions.

## 5. Recommended Setup Workflow

Use this order when setting up the plugin for the first time:

1. Configure institution settings.
2. Add taxonomies: Session Years, Examinations, Boards, Departments/Groups.
3. Add Subjects.
4. Add Students.
5. Add Results.
6. Create a front-end result search page.
7. Test result search using real student registration and roll numbers.

## 6. Configure Institution Settings

Go to:

**EDU Results > Settings**

The settings page stores institution information shown on the result sheet.

Available settings:

| Setting | Description |
| --- | --- |
| Logo | Institution logo URL or uploaded media image. |
| Collage Name | Institution name. The plugin uses the word `Collage` in option names and labels, but it means college/institution. |
| Collage Registration Number | Institution registration number. |
| Collage Since Year | Founding year or since year. |
| Collage Phone Number | Institution phone number. |
| Collage Email Address | Institution email. |
| College Website URL | Institution website. |
| Result Page Banner Heading | Heading displayed on the result sheet. |
| Collage Address | Institution address. |

After editing settings, click **Save Settings**.

## 7. Add Session Years

Session Years are used to categorize students and results.

Example values:

- 2023
- 2024
- 2025
- 2025-2026

Go to the Session Years taxonomy screen under the EDU Results area and add the required years.

## 8. Add Examinations

Examinations are used in the result search form.

Example values:

- Annual Examination
- Half-Yearly Examination
- Test Examination
- SSC
- HSC
- Final Examination

The front-end search form displays these values in the **Examination** dropdown.

## 9. Add Boards

Boards identify the education board or authority.

Example values:

- Dhaka
- Chittagong
- Rajshahi
- Madrasa
- Technical
- Internal Board

The front-end search form displays these values in the **Board** dropdown.

## 10. Add Departments/Groups

Departments/Groups identify student academic groups.

Example values:

- Science
- Commerce
- Humanities
- Arts
- Business Studies
- Computer Science

The front-end search form displays these values in the **Department/Group** dropdown.

## 11. Add Subjects

Go to the **Subjects** screen and click **Add New**.

For each subject:

1. Enter the subject name as the title.
2. Enter the subject code in the subject meta box.
3. Publish the subject.

Example:

| Field | Example |
| --- | --- |
| Subject title | Mathematics |
| Subject code | 101 |

Subjects are later selected inside result records when adding marks/grades.

## 12. Add Students

Go to the **Students** screen and click **Add New**.

The student title is the student name. The plugin changes the title placeholder to **Enter Student Name**.

### Student Basic Information

| Field | Notes |
| --- | --- |
| ID Number | Student ID. Required by UI label. |
| Registration Number | Important. Used to connect students to results. Should be unique. |
| Date of Birth | Student date of birth. |
| Gender | Student gender. |
| Blood Group | Student blood group. |

### Contact Information

| Field | Notes |
| --- | --- |
| Phone Number | Student phone. |
| Email Address | Student email. |
| Guardian Phone | Guardian phone. |
| Address | Student address. |

### Family Information

| Field | Notes |
| --- | --- |
| Father's Name | Used in result auto-fill. |
| Father's Occupation | Optional. |
| Father's Qualification | Optional. |
| Mother's Name | Used in result auto-fill. |
| Mother's Occupation | Optional. |
| Mother's Qualification | Optional. |

### Government Documents

| Field | Notes |
| --- | --- |
| Birth Registration Number | Optional government identity field. |
| NID Number | Optional national ID field. |

### Student Photo

Set the WordPress featured image for the student if you want a student photo.

## 13. Add Results

Go to **Results > Add New Result**.

The title placeholder is **Student Name**, but the plugin can automatically update the result title based on the selected registration number after saving.

### Result Student Selection

| Field | Notes |
| --- | --- |
| Registration Number | Type the student's registration number. Autocomplete is available after at least two characters. |
| Roll Number | Required for front-end result lookup. |
| Student Name | Auto-filled from the student record. |
| Father's Name | Auto-filled from the student record. |
| Mother's Name | Auto-filled from the student record. |

### Result Details

| Field | Notes |
| --- | --- |
| Student Type | Text/classification field. |
| Result Status | Passed or Failed. |
| GPA | Final GPA. |
| GPA (WAS) | GPA without additional subject. |

### Result Taxonomies

For each result, select:

- Session Year
- Examination
- Board
- Department/Group

These are important. The front-end search uses them to locate the result.

### Subject Marks/Grades

The result editor includes a **Subjects Information** repeater table.

For each row:

1. Select a subject.
2. Enter marks or grade.
3. Click **Add Another Subject** to add more rows.
4. Click **Remove** to delete a row.

The result sheet displays subject rows with:

- Subject name
- Marks/grade value
- Letter grade
- GPA

When the subject value is numeric, the plugin can convert marks into a grade using its grade conversion logic.

## 14. Grade Conversion Rules

The main plugin class includes this mark-to-grade conversion:

| Marks | Letter Grade | GPA |
| --- | ---: | ---: |
| 80 or higher | A+ | 5.00 |
| 70-79 | A | 4.00 |
| 60-69 | A- | 3.50 |
| 50-59 | B | 3.00 |
| 40-49 | C | 2.00 |
| 33-39 | D | 1.00 |
| Below 33 | F | 0.00 |

## 15. Create the Public Result Search Page

1. Go to **Pages > Add New**.
2. Create a page such as **Result Search**.
3. Add this shortcode:

```text
[cbedu_search_form]
```

4. Publish the page.
5. Open the page on the front end.

A full-width page template is recommended because the result sheet uses a wide layout.

## 16. Front-End Search Fields

The public search form asks for:

- Examination
- Year
- Board
- Department/Group
- Registration Number
- Roll

All fields are required. If any field is missing, the form shows a validation message and does not search.

## 17. How Result Search Works

When a visitor submits the form:

1. JavaScript validates all required fields.
2. AJAX sends the request to WordPress admin AJAX.
3. The plugin checks the security nonce.
4. The plugin searches `cbedu_results` posts using:
   - selected examination taxonomy term
   - selected session year taxonomy term
   - selected board taxonomy term
   - selected department/group taxonomy term
   - registration number post meta
   - roll number post meta
5. If a matching result is found, the result sheet is returned and displayed without refreshing the page.

## 18. Printing Results

The plugin includes a print script with the function:

```text
cbeduPrintResult(tableId)
```

The result output includes print support so a visitor or admin can print the result sheet.

## 19. Common Admin Mistakes

### Result not found on front end

Usually caused by one of these:

- Wrong registration number.
- Wrong roll number.
- Result record does not have matching taxonomy terms.
- Visitor selected a different year/examination/board/group than the result record.
- Result record is not published.

### Student name does not auto-fill

Check that:

- The student exists.
- The student registration number exactly matches the typed registration number.
- JavaScript is enabled in the browser.
- You are editing a Result post, not a Student post.

### Subject does not appear in result repeater

Check that:

- The subject is published.
- You are editing a Result post.
- The admin page loaded correctly without JavaScript errors.

## 20. Recommended Data Entry Standard

To avoid duplicate or missing results:

- Use a consistent format for registration numbers.
- Use a consistent format for roll numbers.
- Create taxonomy terms before entering results.
- Do not create multiple students with the same registration number.
- Test a few records after every bulk entry/import.
