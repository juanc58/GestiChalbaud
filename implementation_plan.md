# Final Enhancements and System Polish

This plan outlines the implementation of final critical features and UI improvements to ensure data integrity and a premium user experience.

## User Review Required

> [!IMPORTANT]
> The import of the `venezuela.sql` file will add thousands of records to the database to support the State/Municipality/Parish picklists. Please ensure the database server has enough storage and permissions to execute this import.

> [!WARNING]
> The age restriction (max 17 years) will prevent the registration of adult students. Please confirm if there are any exceptions (e.g., special education or night classes) before we enforce this globally.

## Proposed Changes

### 1. Legacy Graduates Support
Allow adding students who graduated before the system was implemented.
- **Backend:** Update `StudentRegistrationService` to support "Egresado" status and optional section assignment.
- **Frontend:** Add a "Registrar como Graduado Histórico" toggle in the registration form.

### 2. Global Form Improvements (Premium UI)
Standardize all forms with asterisks and field-specific error messages.
- **Component:** Create `x-form-field` Blade component for consistent styling.
- **Toast Notifications:** Implement a global floating notification system for success/error alerts.
- **Validation:** Ensure all server-side errors are displayed exactly under each field.

### 3. Student Registration Enhancements
- **Multi-Step Wizard:** Convert the long registration form into a 5-6 step wizard for better UX.
- **Age Restriction:** Enforce a maximum age of 17 years at the moment of registration.
- **Cédula Auto-format:** Add JavaScript to automatically format identity numbers with dots (e.g., 12.345.678).

### 4. Picklists & Data Normalization

#### 4.1 Anthropological Data (Sizes)
- Replace text inputs for Shirt, Pants, and Shoes sizes with dropdowns containing standard values.

#### 4.2 Allergies Picklist
- **Database:** Create `allergies` table and pivot `allergy_student`.
- **UI:** Implement a multi-select picklist with an "Other" comment field.

#### 4.3 Venezuela Administrative Structure (States/Municipios/Parroquias)
- **Database:** Import `venezuela.sql` data.
- **Logic:** Implement dependent dropdowns using an internal API (AJAX) for high performance.
- **API:** Create `LocationController` to serve states, municipalities, and parishes.

#### 4.4 Interests and Recreational Activities
- **Database:** Create `activities` table and pivot `activity_student`.
- **UI:** Implement a multi-select picklist with an "Other" comment field.

### 5. Security Polish
- **Password Strength Meter:** Add a visual indicator when changing passwords in User Management or Profile modules.

## Verification Plan

### Automated Tests
- `php artisan migrate --seed` to verify schema and data imports.
- Validation logic tests for age limits and unique constraints.

### Manual Verification
- Test the registration wizard from start to finish.
- Verify that selecting a State correctly filters the Municipalities via AJAX.
- Check that "Other" fields correctly save supplemental information.
