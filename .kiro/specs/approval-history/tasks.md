# Implementation Plan: Approval History Feature

- [x] 1. Update APAR history views to display creator and approver information





  - Update `resources/views/apar/riwayat.blade.php` to add "Dibuat oleh" and "Di-approve oleh" columns
  - Update controller to eager load user and approver relationships
  - Add visual indicators for approval status with user names
  - _Requirements: 1.1, 1.2, 3.2_

- [x] 2. Update APAR detail view to show complete approval history





  - Update `resources/views/apar/view-kartu.blade.php` to display creator and approver information
  - Add approval timeline section showing creation and approval timestamps
  - Display user roles (Petugas/Leader/Superadmin) alongside names
  - Handle null values gracefully for deleted users
  - _Requirements: 1.3, 3.3, 4.4_

- [x] 3. Update approval pages to show creator information





  - Update `resources/views/admin/approvals/index.blade.php` to display creator name
  - Update `resources/views/admin/approvals/show.blade.php` to show creator details
  - Update `resources/views/leader/approvals/index.blade.php` to display creator name
  - Update `resources/views/leader/approvals/show.blade.php` to show creator details
  - _Requirements: 2.1, 2.2_

- [x] 4. Update controllers to eager load user relationships





  - Update `app/Http/Controllers/AparController.php` riwayat method
  - Update `app/Http/Controllers/Admin/ApprovalController.php` index and show methods
  - Update `app/Http/Controllers/Leader/ApprovalController.php` index and show methods
  - Add eager loading for user and approver relationships
  - _Requirements: 1.1, 1.2, 2.1_

- [x] 5. Apply same updates to other equipment modules (APAT, APAB, Fire Alarm, Box Hydrant, Rumah Pompa, P3K)





  - Update history views for all equipment types
  - Update detail views for all equipment types
  - Update controllers to eager load relationships
  - Ensure consistent UI/UX across all modules
  - _Requirements: 1.1, 1.2, 1.3_

- [x] 6. Add filter functionality for creator and approver





  - Add filter form to history pages
  - Implement filter logic in controllers
  - Add URL parameter handling for filters
  - Add "Clear Filters" button
  - _Requirements: 5.1, 5.2, 5.3, 5.4_

- [x] 7. Update export functionality to include approval history




  - Update `app/Exports/RekapExport.php` to include creator and approver columns
  - Update `resources/views/exports/rekap-pdf.blade.php` to display approval history
  - Test PDF export formatting
  - Test Excel export with new columns
  - _Requirements: 4.5, 5.5_

- [x] 8. Add helper methods for displaying user information





  - Add method to handle deleted users gracefully
  - Add method to format approval status with user info
  - Add method to get user role display name
  - Update views to use helper methods
  - _Requirements: 4.4_

- [x] 9. Write unit tests for approval history display




  - Test user relationship returns correct data
  - Test deleted user handling
  - Test approval status display logic
  - Test filter query results
  - _Requirements: All_

- [x] 10. Final checkpoint - Ensure all tests pass and features work





  - Test creating kartu as different users
  - Test approving as leader and superadmin
  - Verify history displays correct names
  - Test filters and search functionality
  - Test with deleted users
  - Test print layouts
  - Ensure all tests pass, ask the user if questions arise.
