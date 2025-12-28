# Requirements Document

## Introduction

Fitur ini menambahkan informasi riwayat lengkap pada kartu kendali, menampilkan siapa yang membuat kartu kendali (petugas) dan siapa yang meng-approve (leader/admin). Informasi ini akan ditampilkan di halaman riwayat dan detail kartu kendali untuk memberikan transparansi dan akuntabilitas dalam proses inspeksi dan approval.

## Glossary

- **Kartu Kendali**: Dokumen inspeksi untuk peralatan (APAR, APAT, APAB, Fire Alarm, Box Hydrant, Rumah Pompa, P3K)
- **Petugas**: User dengan role 'user' yang membuat kartu kendali
- **Leader**: User dengan role 'leader' yang dapat meng-approve kartu kendali
- **Superadmin**: User dengan role 'superadmin' yang dapat meng-approve kartu kendali
- **Approval History**: Informasi lengkap tentang siapa yang membuat dan meng-approve kartu kendali
- **System**: Aplikasi web PLN untuk manajemen kartu kendali

## Requirements

### Requirement 1

**User Story:** As a superadmin, I want to see who created and approved each kartu kendali, so that I can track accountability and audit the inspection process.

#### Acceptance Criteria

1. WHEN a superadmin views the kartu kendali history list THEN the system SHALL display the creator's name (petugas) for each kartu
2. WHEN a superadmin views the kartu kendali history list THEN the system SHALL display the approver's name and approval timestamp for approved kartu
3. WHEN a superadmin views a specific kartu kendali detail THEN the system SHALL display complete approval history including creator name, creation timestamp, approver name, and approval timestamp
4. WHEN a kartu kendali is pending approval THEN the system SHALL display "Belum di-approve" status with no approver information
5. WHEN a kartu kendali is approved THEN the system SHALL display the approver's full name and role (Leader/Superadmin)

### Requirement 2

**User Story:** As a leader, I want to see who created each kartu kendali in my unit, so that I can verify the inspector's work before approving.

#### Acceptance Criteria

1. WHEN a leader views pending approvals THEN the system SHALL display the creator's name and creation timestamp for each kartu
2. WHEN a leader views the approval detail page THEN the system SHALL display the creator's full name, username, and role
3. WHEN a leader approves a kartu kendali THEN the system SHALL record the leader's user ID and approval timestamp
4. WHEN a leader views approved kartu history THEN the system SHALL display both creator and approver information
5. WHERE a kartu was created by a user from a different unit THEN the system SHALL still display the creator's information

### Requirement 3

**User Story:** As a user (petugas), I want to see my name displayed as the creator of kartu kendali I submitted, so that I can verify my submissions and track their approval status.

#### Acceptance Criteria

1. WHEN a user creates a kartu kendali THEN the system SHALL automatically record the user's ID as the creator
2. WHEN a user views the kartu kendali history THEN the system SHALL display the user's name as "Dibuat oleh"
3. WHEN a user views a kartu detail THEN the system SHALL display complete information including creator name and approval status
4. WHEN a kartu is approved THEN the system SHALL display who approved it and when
5. WHEN a kartu is pending THEN the system SHALL display "Menunggu approval" status

### Requirement 4

**User Story:** As a system administrator, I want the approval history to be immutable and auditable, so that we maintain data integrity and compliance.

#### Acceptance Criteria

1. WHEN a kartu kendali is created THEN the system SHALL record the creator's user_id and creation timestamp
2. WHEN a kartu kendali is approved THEN the system SHALL record the approver's user_id and approval timestamp
3. WHEN displaying approval history THEN the system SHALL use database relationships to fetch current user information
4. IF a user account is deleted THEN the system SHALL maintain the historical record with "User Deleted" placeholder
5. WHEN exporting reports THEN the system SHALL include creator and approver information in all exports

### Requirement 5

**User Story:** As a superadmin, I want to filter and search kartu kendali by creator or approver, so that I can analyze performance and identify patterns.

#### Acceptance Criteria

1. WHEN viewing the kartu history list THEN the system SHALL provide filter options for creator name
2. WHEN viewing the kartu history list THEN the system SHALL provide filter options for approver name
3. WHEN viewing the kartu history list THEN the system SHALL provide filter options for approval status (approved/pending)
4. WHEN applying filters THEN the system SHALL display matching results with complete approval history
5. WHEN exporting filtered data THEN the system SHALL include all approval history fields in the export
