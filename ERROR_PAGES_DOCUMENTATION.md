# Custom Error Pages Documentation

## Overview

ThreadLoop now includes a comprehensive set of custom error pages with modern UI/UX design that seamlessly integrates with the application's theme. All error pages are responsive and provide a consistent user experience.

## Error Pages Included

### 1. 404 - Page Not Found

-   **File**: `resources/views/errors/404.blade.php`
-   **Description**: Displays when a requested page doesn't exist
-   **Features**:
    -   Search icon with question mark overlay
    -   Gradient color scheme (blue to pink)
    -   "Go Home" and "Go Back" buttons
    -   Helpful suggestions for navigation

### 2. 403 - Access Forbidden

-   **File**: `resources/views/errors/403.blade.php`
-   **Description**: Shows when user lacks permission to access a resource
-   **Features**:
    -   Lock icon with stop sign overlay
    -   Gradient color scheme (purple to red)
    -   Context-aware buttons (Sign In for guests, Dashboard for authenticated users)
    -   Permission guidance

### 3. 419 - Session Expired

-   **File**: `resources/views/errors/419.blade.php`
-   **Description**: Displays when CSRF token expires or session times out
-   **Features**:
    -   Clock icon with warning overlay
    -   Gradient color scheme (yellow to red)
    -   Refresh page functionality
    -   Session management guidance

### 4. 429 - Too Many Requests

-   **File**: `resources/views/errors/429.blade.php`
-   **Description**: Shows when rate limiting is triggered
-   **Features**:
    -   Speedometer icon with stop overlay
    -   Gradient color scheme (indigo to pink)
    -   Live countdown timer (60 seconds)
    -   Auto-refresh after countdown
    -   Rate limiting explanation

### 5. 500 - Internal Server Error

-   **File**: `resources/views/errors/500.blade.php`
-   **Description**: Displays for server-side errors
-   **Features**:
    -   Server rack icon with warning overlay
    -   Gradient color scheme (red to yellow)
    -   Try again functionality
    -   Support contact information

### 6. 503 - Service Unavailable

-   **File**: `resources/views/errors/503.blade.php`
-   **Description**: Shows during maintenance or service downtime
-   **Features**:
    -   Tools icon with clock overlay
    -   Gradient color scheme (gray to indigo)
    -   Maintenance messaging
    -   Retry functionality

## Layout System

### Error Layout

-   **File**: `resources/views/errors/layout.blade.php`
-   **Features**:
    -   Consistent navigation header
    -   ThreadLoop branding
    -   Responsive design
    -   Dark mode support
    -   Context-aware navigation links

## Configuration

### Exception Handler

The error pages are automatically configured in `bootstrap/app.php`:

```php
->withExceptions(function (Exceptions $exceptions) {
    // Handles 404 errors
    $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Page not found'], 404);
        }
        return response()->view('errors.404', [], 404);
    });

    // Handles HTTP exceptions (403, 419, 429, 500, 503)
    $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
        // ... error handling logic
    });

    // Handles database errors
    $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
        // ... database error handling
    });

    // Handles general exceptions
    $exceptions->render(function (\Exception $e, $request) {
        // ... general error handling
    });
})
```

## Design Features

### Visual Elements

-   **Large Error Codes**: Prominent display of error numbers
-   **Gradient Lines**: Color-coded gradient lines under error codes
-   **Illustrations**: Custom SVG icons with contextual overlays
-   **Floating Elements**: Animated dots for visual interest
-   **Responsive Design**: Optimized for all screen sizes

### Color Schemes

Each error page has a unique color scheme:

-   **404**: Blue to Pink gradient
-   **403**: Purple to Red gradient
-   **419**: Yellow to Red gradient
-   **429**: Indigo to Pink gradient
-   **500**: Red to Yellow gradient
-   **503**: Gray to Indigo gradient

### Animations

-   **Floating Animation**: Icons gently float up and down
-   **Pulse Animation**: Floating elements pulse with staggered timing
-   **Hover Effects**: Interactive elements respond to user interaction
-   **Countdown Timer**: Live countdown for 429 errors

## Custom CSS

### Error Page Styles

Additional CSS has been added to `resources/css/app.css`:

```css
/* Error Pages Styles */
.error-page-container {
    /* ... */
}
.error-code {
    /* ... */
}
.error-gradient-line {
    /* ... */
}
.error-illustration {
    /* ... */
}
.error-floating-element {
    /* ... */
}

/* Custom animations */
@keyframes float {
    /* ... */
}
@keyframes pulse-glow {
    /* ... */
}

/* Responsive adjustments */
@media (max-width: 640px) {
    /* ... */
}
```

## Testing

### Test Routes

Temporary test routes are available for development:

-   `/test/404` - Test 404 error page
-   `/test/403` - Test 403 error page
-   `/test/419` - Test 419 error page
-   `/test/429` - Test 429 error page
-   `/test/500` - Test 500 error page
-   `/test/503` - Test 503 error page

**Note**: Remove these test routes in production.

## Responsive Design

### Mobile Optimization

-   Reduced error code size on mobile devices
-   Smaller illustrations for better mobile experience
-   Stacked button layout on small screens
-   Touch-friendly interactive elements

### Dark Mode Support

-   Automatic dark mode detection
-   Consistent dark theme across all error pages
-   Proper contrast ratios for accessibility
-   Smooth transitions between light and dark modes

## Accessibility Features

-   **Semantic HTML**: Proper heading structure and landmarks
-   **ARIA Labels**: Screen reader friendly descriptions
-   **Keyboard Navigation**: Full keyboard accessibility
-   **Color Contrast**: WCAG compliant color combinations
-   **Focus Management**: Clear focus indicators

## Integration with ThreadLoop Theme

### Brand Consistency

-   Uses ThreadLoop logo and branding
-   Matches application color scheme
-   Consistent typography (Instrument Sans font)
-   Same navigation patterns as main application

### User Experience

-   Context-aware navigation links
-   Authentication state consideration
-   Helpful error messages in Indonesian
-   Clear call-to-action buttons

## Maintenance

### Adding New Error Pages

1. Create new Blade template in `resources/views/errors/`
2. Add error code handling in `bootstrap/app.php`
3. Update CSS if custom styling is needed
4. Test across different devices and browsers

### Customization

-   Modify color schemes in individual error page templates
-   Adjust animations in `resources/css/app.css`
-   Update error messages for localization
-   Customize illustrations and icons

## Performance Considerations

-   **Lightweight**: Minimal CSS and JavaScript
-   **Optimized Images**: SVG icons for crisp display
-   **Efficient Animations**: CSS-based animations for smooth performance
-   **Lazy Loading**: Error pages load quickly
-   **Caching**: Static error pages can be cached effectively

This error page system provides a professional, user-friendly experience that maintains ThreadLoop's brand identity while helping users navigate errors gracefully.
