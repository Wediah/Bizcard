# Technical Documentation

## Service Layer Documentation

### AnalyticsService
- **Purpose:** Centralizes analytics logic for tracking page views and visitor sessions.
- **Usage:**
  - `trackPageView(Request $request)`: Records a page view and updates visitor session.
  - Handles session management, location lookup, and metadata collection.
  - Uses cookies for visitor/session IDs and Stevebauman\Location for geolocation.

## Code Comments
- Complex business logic in AnalyticsService and Livewire components is commented for clarity.
- Example (from AnalyticsService):
  ```php
  /**
   * Track a page view and update visitor session.
   */
  public function trackPageView(Request $request): PageView
  {
      // ...
  }
  ```
- All service methods and key logic blocks are documented inline.

## Testing Documentation
- **Test Coverage:**
  - Feature tests for authentication, dashboard, profile, password, two-factor, and settings.
  - Unit tests for core logic and example cases.
- **Testing Strategy:**
  - Use PHPUnit for automated testing.
  - Feature tests simulate user interactions and verify system behavior.
  - Unit tests validate isolated business logic.
- **How to Run Tests:**
  ```bash
  php artisan test
  ```
- **Test Files:**
  - Located in `tests/Feature/` and `tests/Unit/`.
  - Example: `tests/Feature/DashboardTest.php`, `tests/Feature/Settings/ProfileUpdateTest.php`.

---

For further details, see inline code comments and individual test files.

