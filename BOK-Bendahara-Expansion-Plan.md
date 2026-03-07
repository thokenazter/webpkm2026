
# BOK-Bendahara-Expansion-Plan.md
**Purpose:**  
This document defines the full technical expansion plan for the BOK Treasurer Management Module within the Laravel-based health-center system.  
Codex must execute and implement all listed modules in sequence inside the local repository.

---

## 1. MODULE STRUCTURE & NAMESPACE
All new features must live under:

app/Modules/BOK/

swift
Copy code

Namespace prefix:
App\Modules\BOK

vbnet
Copy code

Each major feature should be structured as:
/Models
/Services
/Http/Controllers
/Http/Livewire
/Policies
/Resources

yaml
Copy code

---

## 2. CORE MODULES TO IMPLEMENT

### 2.1 Ledger & Cashbook System
Create a unified ledger system to record all daily financial activities for the BOK Treasurer.
- Purpose: digital BKU (Buku Kas Umum) with bank/cash/tax sub-ledgers.
- Features:
  - Record every debit/credit from all SPJ and fund receptions.
  - Auto-posting from LPJ/SPJ approvals.
  - Monthly closing & lock periods.
  - Running balance tracking.
  - Export BKU (Excel/PDF).

Dependencies:
- LPJ approval events trigger ledger posting.
- Connect to Tax and BankReconciliation modules.

---

### 2.2 Bank Reconciliation
Implements monthly reconciliation between Ledger bank balance and actual bank statements.
- Import CSV/Excel bank statements.
- Auto-match transactions (date + amount).
- Flag unmatched entries.
- Generate reconciliation report.
- Store reconciliation history per month/year.

---

### 2.3 Tax Management Module
Create automated tax handling for all BOK expenditures.
- Handles PPN, PPh21, PPh22, PPh23.
- Generates e-Billing schedule reminders.
- Stores payment evidence (file upload).
- Tracks tax status: Pending → Paid → Verified.
- Integrates with Ledger (posting tax deduction and settlement).

---

### 2.4 SPJ Checklist & Verification Workflow
Implements verification system for SPJ completeness and validity.
- Each SPJ type (Travel, Meeting, Purchase, Honorarium) has checklist rules.
- SPJ Workflow States:
Draft → Submitted → Verified (Treasurer) → Approved (PPTK/KTU) → Signed (Head of Puskesmas) → Paid → Posted

yaml
Copy code
- Required actions:
- Prevent submission until all checklist items are complete.
- Attach document uploads for each requirement.
- Allow Treasurer to reject with comments.
- Store verification history.

Integrate red-flag detection:
- Detect duplicated travel dates, participant overlap, inflated quantities, missing store stamp, etc.
- Display red-flag warnings in Treasurer dashboard.

---

### 2.5 Template Manager
Centralized document template system.
- Manage Word/Excel templates for LPJ, TB, SPPT, RAB.
- Version control of templates.
- Variable validation before regeneration.
- UI in Filament for upload/update.
- Store history for rollback.

---

### 2.6 Queue-Based Document Generation
All Word/ZIP generations must use Laravel Queue.
- Prevent blocking on heavy export.
- Display progress indicator.
- Store logs of generation jobs.

---

### 2.7 Reporting System
Implements the official reporting workflow for BOK.

#### 2.7.1 Monthly Realization Reports
- Generate automatically from Ledger.
- Format must follow Permendagri 12/2023 standard:
- Income realization
- Expense realization per menu/category
- Export as Excel/PDF.

#### 2.7.2 Annual LPJ Compilation
- Aggregate all 12 months.
- Include Treasurer and Head of Puskesmas signatures.
- Include Ledger reconciliation summary.
- Export as PDF/Word.

#### 2.7.3 BOK Salur Export
- Generate JSON/Excel output ready for upload to the official BOK Salur portal.
- Include metadata: period, total realization, activity breakdown.
- Optional manual upload interface.

---

### 2.8 Reminder & Scheduler
- Monthly reminders for:
- Report submission deadlines.
- Tax payment deadlines.
- Cut-off dates (as per SE LTA).
- Implement Laravel scheduler + notification channel (email/Filament dashboard).

---

## 3. DATA ENTITIES (Summary Only)
Codex must create or expand models accordingly:
- LedgerEntry
- TaxEntry
- BankReconciliation
- SpjChecklist
- SpjChecklistItem
- SpjVerificationLog
- ReportMonthly
- ReportAnnual
- Template
- JobExportLog

Each model must include timestamps, user tracking (`created_by`, `updated_by`), and soft deletes.

---

## 4. SERVICE LAYER RESPONSIBILITIES
Codex must implement service classes under `App\Modules\BOK\Services`:

| Service | Responsibility |
|----------|----------------|
| **LedgerPostingService** | Handles posting of debit/credit from LPJ/SPJ approvals into the ledger. |
| **TaxService** | Calculates, schedules, and verifies tax obligations. |
| **ReconciliationService** | Matches ledger vs bank statements. |
| **ChecklistService** | Manages checklist generation, verification, and red-flag detection. |
| **ReportGeneratorService** | Builds monthly/annual realization reports and BOK Salur exports. |
| **TemplateManagerService** | Manages template versioning and validation. |
| **NotificationService** | Handles reminders and scheduler tasks. |

Each service must expose public methods with clear single responsibility (SRP).

---

## 5. STATE MACHINES

### 5.1 SPJ Workflow
[Draft]
↓ submit()
[Submitted]
↓ verify()
[Verified]
↓ approve()
[Approved]
↓ sign()
[Signed]
↓ pay()
[Paid]
↓ postToLedger()
[Posted]

markdown
Copy code

- Transitions logged to `spj_verification_logs`.
- Each transition triggers events for Notification + LedgerPostingService.

### 5.2 Reporting Period Workflow
Open → Review → Locked → Submitted → Closed

yaml
Copy code
- Locked = Ledger closed for that month.
- Closed = Report approved and uploaded to Dinkes.

---

## 6. USER ROLES & ACCESS CONTROL
Use existing Filament roles or policies:
| Role | Access |
|------|---------|
| Treasurer | Manage ledger, verify SPJ, generate reports |
| PPTK/KTU | Approve verified SPJ |
| Head of Puskesmas | Final signature |
| Staff/User | Create and submit SPJ |
| Admin/SuperAdmin | Full access, manage templates, unlock periods |

---

## 7. FRONTEND (FILAMENT / LIVEWIRE)
- Add new Filament pages for:
  - **Ledger Dashboard** (summary, filters, export)
  - **SPJ Verification Queue**
  - **Bank Reconciliation UI**
  - **Monthly Report Generator**
  - **Template Manager**
  - **Tax Dashboard**
- Add widgets: total disbursed funds, outstanding taxes, red-flag count.

---

## 8. LOGGING & AUDIT TRAIL
- Extend `spatie/laravel-activitylog` usage for:
  - SPJ workflow transitions.
  - Ledger postings.
  - Report generations.
  - Template modifications.
- All logs visible to Admin in Filament Audit Log page.

---

## 9. ROADMAP EXECUTION (FOR CODEX)

### Sprint 1 — Quick Wins
1. Implement SPJ Checklist + Workflow engine.
2. Add Red-Flag detection & Filament UI.
3. Add Template Manager module.
4. Move document generation to Queue system.

### Sprint 2 — Financial Core
1. Create Ledger system with auto-posting.
2. Add Bank Reconciliation.
3. Build Tax module with reminder scheduler.

### Sprint 3 — Reporting & Export
1. Generate Monthly Realization Reports.
2. Compile Annual LPJ Report.
3. Implement BOK Salur Export.

### Sprint 4 — Automation & Audit
1. Implement scheduler reminders (reports & taxes).
2. Extend audit logs and dashboard summaries.
3. Finalize data locking & annual closing.

---

## 10. TESTING BLUEPRINT
Codex must implement full testing coverage:

| Category | Key Tests |
|-----------|------------|
| Unit | Ledger posting logic, tax calculations, checklist validation |
| Feature | SPJ state transitions, file upload validations, red-flag detection |
| Integration | Reconciliation accuracy, report totals, BOK Salur export integrity |
| UI | Filament access roles, template upload, export queue jobs |

---

## 11. DELIVERABLES
Codex must deliver:
- Fully functional BOK Treasurer Management modules as described.
- Filament-based interface for all operations.
- Automated tests passing.
- Compatible exports for BOK Salur and audit review.

---

**End of Specification**