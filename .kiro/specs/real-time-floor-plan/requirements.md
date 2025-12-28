# Requirements Document

## Introduction

Sistem denah real-time adalah fitur untuk menampilkan lokasi peralatan keselamatan (APAR, APAT, Fire Alarm, Box Hydrant, Rumah Pompa, APAB, P3K) pada denah gedung secara visual dan interaktif. Fitur ini memungkinkan petugas untuk melihat posisi peralatan secara real-time dengan tampilan yang modern dan informatif.

## Glossary

- **System**: Aplikasi inventaris PLN yang mengelola peralatan keselamatan
- **Floor Plan**: Denah gedung yang menampilkan tata letak ruangan dan lokasi peralatan
- **Equipment**: Peralatan keselamatan seperti APAR, APAT, Fire Alarm, Box Hydrant, Rumah Pompa, APAB, dan P3K
- **Marker**: Penanda visual pada denah yang menunjukkan lokasi peralatan
- **Real-time**: Data yang ditampilkan selalu sinkron dengan database terkini
- **Petugas**: User dengan role yang dapat mengakses sistem untuk melihat dan mengelola peralatan
- **Unit**: Unit organisasi yang memiliki denah dan peralatan tersendiri

## Requirements

### Requirement 1

**User Story:** As a petugas, I want to view a floor plan with equipment locations, so that I can quickly identify where safety equipment is positioned in the building.

#### Acceptance Criteria

1. WHEN a petugas accesses the floor plan page THEN the System SHALL display an interactive floor plan image with all equipment markers overlaid
2. WHEN the floor plan loads THEN the System SHALL fetch and display current equipment data from the database
3. WHEN equipment data is retrieved THEN the System SHALL place visual markers on the floor plan based on equipment coordinates
4. WHERE a unit has a custom floor plan image THEN the System SHALL display that unit's specific floor plan
5. WHEN no floor plan image exists for a unit THEN the System SHALL display a default placeholder with a message

### Requirement 2

**User Story:** As a petugas, I want to see different visual markers for each equipment type, so that I can easily distinguish between APAR, APAT, Fire Alarm, and other equipment.

#### Acceptance Criteria

1. WHEN displaying equipment markers THEN the System SHALL use distinct colors for each equipment type
2. WHEN displaying APAR markers THEN the System SHALL render them with a red color indicator
3. WHEN displaying APAT markers THEN the System SHALL render them with a blue color indicator
4. WHEN displaying Fire Alarm markers THEN the System SHALL render them with an orange color indicator
5. WHEN displaying Box Hydrant markers THEN the System SHALL render them with a cyan color indicator
6. WHEN displaying Rumah Pompa markers THEN the System SHALL render them with a purple color indicator
7. WHEN displaying APAB markers THEN the System SHALL render them with a green color indicator
8. WHEN displaying P3K markers THEN the System SHALL render them with a pink color indicator

### Requirement 3

**User Story:** As a petugas, I want to click on equipment markers to see detailed information, so that I can access equipment details without leaving the floor plan view.

#### Acceptance Criteria

1. WHEN a petugas clicks on an equipment marker THEN the System SHALL display a popup with equipment details
2. WHEN the popup displays THEN the System SHALL show equipment serial number, type, location name, and status
3. WHEN the popup displays THEN the System SHALL provide a link to view full equipment details
4. WHEN a petugas clicks outside the popup THEN the System SHALL close the popup
5. WHEN a petugas clicks the close button on the popup THEN the System SHALL close the popup

### Requirement 4

**User Story:** As a petugas, I want to filter equipment by type on the floor plan, so that I can focus on specific equipment categories.

#### Acceptance Criteria

1. WHEN the floor plan displays THEN the System SHALL provide filter controls for each equipment type
2. WHEN a petugas toggles an equipment type filter THEN the System SHALL show or hide markers for that equipment type
3. WHEN all filters are disabled THEN the System SHALL display a message indicating no equipment is visible
4. WHEN a petugas enables a filter THEN the System SHALL immediately update the visible markers without page reload
5. WHEN filters are applied THEN the System SHALL maintain filter state during the session

### Requirement 5

**User Story:** As a petugas, I want to see equipment status indicators on markers, so that I can quickly identify equipment that needs attention.

#### Acceptance Criteria

1. WHEN displaying equipment markers THEN the System SHALL indicate equipment status with visual cues
2. WHEN equipment status is "Baik" THEN the System SHALL display a green status indicator on the marker
3. WHEN equipment status is "Rusak" THEN the System SHALL display a red status indicator on the marker
4. WHEN equipment status is "Perlu Pengecekan" THEN the System SHALL display a yellow status indicator on the marker
5. WHEN equipment has no status THEN the System SHALL display a gray status indicator on the marker

### Requirement 6

**User Story:** As an admin, I want to upload and manage floor plan images for each unit, so that the system can display accurate building layouts.

#### Acceptance Criteria

1. WHEN an admin accesses floor plan management THEN the System SHALL display a form to upload floor plan images
2. WHEN an admin uploads a floor plan image THEN the System SHALL validate the file type is an image format
3. WHEN an admin uploads a floor plan image THEN the System SHALL associate the image with the selected unit
4. WHEN an admin saves a floor plan image THEN the System SHALL store the image in the storage directory
5. WHEN an admin updates a floor plan image THEN the System SHALL replace the existing image for that unit

### Requirement 7

**User Story:** As an admin, I want to set equipment coordinates on the floor plan, so that markers appear in the correct positions.

#### Acceptance Criteria

1. WHEN an admin edits equipment THEN the System SHALL provide fields to input X and Y coordinates
2. WHEN an admin saves equipment with coordinates THEN the System SHALL store the coordinate values in the database
3. WHEN coordinates are provided as percentages THEN the System SHALL position markers relative to floor plan dimensions
4. WHEN equipment has no coordinates THEN the System SHALL not display a marker for that equipment on the floor plan
5. WHEN an admin clicks on the floor plan in edit mode THEN the System SHALL capture and populate coordinate fields

### Requirement 8

**User Story:** As a petugas, I want the floor plan to be responsive and work on mobile devices, so that I can view equipment locations on any device.

#### Acceptance Criteria

1. WHEN the floor plan is accessed on mobile devices THEN the System SHALL display a responsive layout
2. WHEN the floor plan is viewed on small screens THEN the System SHALL allow pinch-to-zoom functionality
3. WHEN the floor plan is viewed on small screens THEN the System SHALL allow pan/drag functionality
4. WHEN markers are tapped on mobile THEN the System SHALL display the equipment popup
5. WHEN the floor plan is rotated on mobile THEN the System SHALL adjust the layout appropriately

### Requirement 9

**User Story:** As a petugas, I want to search for specific equipment on the floor plan, so that I can quickly locate equipment by serial number or name.

#### Acceptance Criteria

1. WHEN the floor plan displays THEN the System SHALL provide a search input field
2. WHEN a petugas types in the search field THEN the System SHALL filter visible markers based on the search query
3. WHEN a search matches equipment THEN the System SHALL highlight the matching marker
4. WHEN a search matches equipment THEN the System SHALL pan the floor plan to center on the matched marker
5. WHEN the search field is cleared THEN the System SHALL reset the view and show all filtered equipment

### Requirement 10

**User Story:** As a petugas, I want to see a legend explaining marker colors and symbols, so that I can understand what each marker represents.

#### Acceptance Criteria

1. WHEN the floor plan displays THEN the System SHALL show a legend panel
2. WHEN the legend displays THEN the System SHALL list all equipment types with their corresponding colors
3. WHEN the legend displays THEN the System SHALL show status indicators and their meanings
4. WHEN a petugas clicks on a legend item THEN the System SHALL highlight all markers of that type
5. WHERE screen space is limited THEN the System SHALL allow the legend to be collapsed or expanded
