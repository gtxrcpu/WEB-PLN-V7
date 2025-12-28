# Design Document: Approval History Feature

## Overview

This feature enhances the kartu kendali system by displaying complete approval history information, showing who created each kartu (petugas) and who approved it (leader/superadmin). The information will be displayed in history lists, detail views, and exports to provide transparency and accountability.

## Architecture

### Database Layer
- Existing tables already have `user_id` (creator) and `approved_by` (approver) columns
- Use Eloquent relationships to fetch user information
- Handle soft-deleted users gracefully

### Controller Layer
- Update existing controllers to eager load user relationships
- Add filter logic for creator/approver search
- Ensure all queries include user relationship data

### View Layer
- Update history list views to display creator and approver names
- Update detail views to show complete approval timeline
- Add visual indicators for approval status
- Ensure print-friendly layouts maintain approval information

## Components and Interfaces

### 1. Model Relationships (Already Exist)

All kartu models (KartuApar, KartuApat, KartuApab, etc.) already have:
```php
public function user() // Creator relationship
{
    return $this->belongsTo(User::class);
}

public function approver() // Approver relationship
{
    return $this->belongsTo(User::class, 'approved_by');
}
```

### 2. Controller Updates

Update controllers to eager load relationships:
```php
// In index/history methods
$kartuKendali = KartuApar::with(['user', 'approver', 'signature'])
    ->where('apar_id', $id)
    ->orderBy('tgl_periksa', 'desc')
    ->get();
```

Add filter functionality:
```php
// In index methods
$query = KartuApar::with(['user', 'approver']);

if ($request->has('creator')) {
    $query->whereHas('user', function($q) use ($request) {
        $q->where('name', 'like', '%' . $request->creator . '%');
    });
}

if ($request->has('approver')) {
    $query->whereHas('approver', function($q) use ($request) {
        $q->where('name', 'like', '%' . $request->approver . '%');
    });
}
```

### 3. View Components

#### History List Table
Add columns for creator and approver:
- Creator Name (from `user` relationship)
- Approver Name (from `approver` relationship, if approved)
- Approval Timestamp (formatted)

#### Detail View
Add approval history section:
- Created by: [User Name] on [Timestamp]
- Approved by: [Approver Name] ([Role]) on [Timestamp]
- Status badge (Approved/Pending)

#### Filter UI
Add filter form:
- Search by creator name
- Search by approver name
- Filter by status (All/Approved/Pending)

## Data Models

### Existing Schema (No Changes Needed)

```sql
-- All kartu tables already have:
user_id (foreign key to users) -- Creator
approved_by (foreign key to users) -- Approver
approved_at (timestamp) -- Approval time
```

### User Model
```php
class User {
    public function kartusCreated() {
        return $this->hasMany(KartuApar::class, 'user_id');
    }
    
    public function kartusApproved() {
        return $this->hasMany(KartuApar::class, 'approved_by');
    }
}
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Creator Information Persistence
*For any* kartu kendali record, if it has a user_id, then fetching the user relationship should return the creator's information or null if user is deleted.
**Validates: Requirements 1.1, 2.1, 3.1, 4.1**

### Property 2: Approver Information Consistency
*For any* approved kartu kendali (approved_at is not null), the approved_by field must contain a valid user_id and the approver relationship should return user information.
**Validates: Requirements 1.2, 2.3, 4.2**

### Property 3: Approval Status Display
*For any* kartu kendali, if approved_at is null then status should display "Pending", otherwise status should display "Approved" with approver information.
**Validates: Requirements 1.4, 3.4, 3.5**

### Property 4: Filter Consistency
*For any* filter query by creator name, all returned results should have a creator whose name matches the filter criteria.
**Validates: Requirements 5.1, 5.4**

### Property 5: Export Data Completeness
*For any* kartu kendali export, each record should include creator name, creation timestamp, approver name (if approved), and approval timestamp (if approved).
**Validates: Requirements 4.5, 5.5**

### Property 6: Deleted User Handling
*For any* kartu kendali where the creator or approver user has been deleted, the system should display "User Deleted" placeholder instead of causing an error.
**Validates: Requirements 4.4**

## Error Handling

### Missing User Relationships
- If `user` relationship is null: Display "Unknown User" or "User Deleted"
- If `approver` relationship is null on approved kartu: Display "Unknown Approver"
- Log warning for data integrity issues

### Database Query Errors
- Wrap relationship queries in try-catch blocks
- Provide fallback display values
- Log errors for debugging

### Display Errors
- Handle null values gracefully in Blade templates
- Use null coalescing operators: `{{ $kartu->user->name ?? 'Unknown' }}`
- Provide default values for missing data

## Testing Strategy

### Unit Tests
1. Test model relationships return correct user data
2. Test null handling when user is deleted
3. Test filter queries return correct results
4. Test approval status logic (isApproved method)

### Integration Tests
1. Test complete flow: create kartu → approve → view history
2. Test filter functionality with various criteria
3. Test export includes all approval history fields
4. Test display with deleted users

### Manual Testing
1. Create kartu as different users
2. Approve as leader and superadmin
3. Verify history displays correct names
4. Test filters and search
5. Test print layout includes approval info
6. Delete a user and verify "User Deleted" displays

## Implementation Notes

### Phase 1: Update Views
- Add creator and approver columns to history tables
- Update detail views with approval history section
- Ensure responsive design

### Phase 2: Add Filters
- Add filter form to history pages
- Implement filter logic in controllers
- Add clear filters button

### Phase 3: Update Exports
- Modify export classes to include approval history
- Update PDF templates with creator/approver info
- Test export formatting

### Phase 4: Handle Edge Cases
- Implement deleted user handling
- Add logging for missing relationships
- Test with various user scenarios

## UI/UX Considerations

### History List
- Add "Dibuat oleh" column showing creator name
- Add "Di-approve oleh" column showing approver name and timestamp
- Use color coding: green for approved, yellow for pending
- Make columns sortable

### Detail View
- Add prominent approval history card at top
- Show timeline: Created → Approved
- Display user avatars if available
- Use icons for visual clarity

### Filters
- Collapsible filter panel
- Auto-complete for user names
- Show active filters as badges
- Preserve filters in URL parameters

### Print Layout
- Include approval history in printed version
- Format timestamps clearly
- Show "Dibuat oleh" and "Di-approve oleh" sections
- Maintain professional appearance
