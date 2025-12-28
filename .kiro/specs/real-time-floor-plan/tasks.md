# Implementation Plan

- [x] 1. Set up database schema and models





  - Create migration for floor_plans table with unit_id, name, image_path, width, height, description, and is_active fields
  - Create migration to add floor_plan_id, floor_plan_x, and floor_plan_y columns to all equipment tables (apars, apats, fire_alarms, box_hydrants, rumah_pompas, apabs, p3ks)
  - Create FloorPlan model with relationships to Unit and methods to retrieve all equipment
  - Add floor plan relationships to all equipment models (Apar, Apat, FireAlarm, BoxHydrant, RumahPompa, Apab, P3k)
  - _Requirements: 1.1, 1.4, 6.3, 6.4, 7.2_

- [x] 2. Implement FloorPlanController with core functionality





  - Create FloorPlanController with index() method to display floor plan view
  - Implement getEquipmentData() method to return JSON of all equipment with coordinates
  - Implement uploadFloorPlan() method with image validation and storage
  - Implement updateEquipmentCoordinates() method to save equipment positions
  - Add helper method to format equipment data for map display
  - Add helper method to get model class by equipment type
  - _Requirements: 1.2, 1.3, 6.1, 6.2, 6.4, 7.1, 7.2_

- [x] 3. Create admin floor plan management interface





  - Create admin floor plan index view with list of uploaded floor plans
  - Create admin floor plan upload form with unit selection, name, image upload, and description fields
  - Create admin floor plan edit view to update floor plan details
  - Add validation for image file types (jpeg, png, jpg, svg) and size limits
  - Display floor plan preview after upload
  - _Requirements: 6.1, 6.2, 6.3, 6.5_

- [x] 4. Add coordinate input to equipment forms





  - Add floor_plan_id, floor_plan_x, and floor_plan_y fields to all equipment create/edit forms
  - Implement coordinate picker UI that shows floor plan and allows clicking to set position
  - Add JavaScript to capture click coordinates and convert to percentage values
  - Display current marker position on floor plan preview in edit mode
  - Validate coordinate values are between 0-100
  - _Requirements: 7.1, 7.2, 7.3, 7.5_

- [x] 5. Build main floor plan view with Alpine.js






  - Create floor-plan/index.blade.php with responsive layout
  - Implement Alpine.js component with equipment data loading
  - Display floor plan image with proper scaling and containment
  - Render equipment markers at correct positions using percentage coordinates
  - Add marker styling with equipment type colors and status indicators
  - Implement pulse animation for equipment with "rusak" status
  - _Requirements: 1.1, 1.2, 1.3, 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7, 2.8, 5.1, 5.2, 5.3, 5.4, 5.5_

- [x] 6. Implement equipment filtering system





  - Create filter panel with checkboxes for each equipment type
  - Add Alpine.js reactive filters object with enabled state for each type
  - Implement updateVisibleMarkers() method to filter equipment based on enabled filters
  - Display equipment count for each filter type
  - Show message when all filters are disabled
  - Maintain filter state during session
  - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_

- [x] 7. Create equipment detail popup modal





  - Implement modal component that displays on marker click
  - Show equipment details: serial number, type, location, and status
  - Add link to view full equipment details page
  - Implement close functionality on outside click and close button
  - Style modal with proper z-index and backdrop
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

- [x] 8. Add search functionality





  - Create search input field in header
  - Implement filterEquipment() method to filter by search query
  - Filter equipment by name and serial number
  - Highlight matching markers on search
  - Pan floor plan to center on matched equipment
  - Reset view when search is cleared
  - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5_

- [x] 9. Implement zoom and pan controls





  - Integrate Panzoom.js library for zoom/pan functionality
  - Add zoom in, zoom out, and reset zoom buttons
  - Configure min/max zoom levels (0.5x to 5x)
  - Enable mouse wheel zoom
  - Enable drag to pan
  - Position zoom controls in bottom-right corner
  - _Requirements: 8.2, 8.3_



- [x] 10. Create legend and statistics panels



  - Create legend panel showing equipment type colors and labels
  - Add status indicator legend with color meanings
  - Display statistics showing total equipment and visible equipment count
  - Implement collapsible legend for small screens
  - Add click handler on legend items to highlight markers of that type
  - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5_

- [x] 11. Implement responsive design for mobile devices





  - Add responsive grid layout that stacks on mobile
  - Implement touch-friendly marker sizes and tap targets
  - Enable pinch-to-zoom gesture support
  - Enable touch drag for panning
  - Adjust layout on device rotation
  - Make filter panel collapsible on mobile
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_


- [x] 12. Add routes and navigation




  - Add route for floor plan view (floor-plan.index)
  - Add route for equipment data API (floor-plan.equipment-data)
  - Add routes for admin floor plan management (admin.floor-plans.*)
  - Add route for updating equipment coordinates API
  - Add navigation menu item for floor plan in main layout
  - Apply appropriate middleware for role-based access
  - _Requirements: 1.1, 6.1_


- [x] 13. Enhance visual design and animations




  - Apply Tailwind CSS styling for modern, clean interface
  - Add hover effects on markers with scale transform
  - Implement smooth transitions for filter changes
  - Add loading states for data fetching
  - Style popup modal with shadow and rounded corners
  - Add icons for equipment types using SVG
  - Implement color scheme matching existing application design
  - _Requirements: 1.1, 2.1, 3.1_

- [x] 14. Add default floor plan handling





  - Implement check for missing floor plan
  - Display placeholder message when no floor plan exists
  - Show helpful message directing users to contact admin
  - Add icon for empty state
  - _Requirements: 1.5_

- [x] 15. Checkpoint - Ensure all tests pass





  - Ensure all tests pass, ask the user if questions arise.
