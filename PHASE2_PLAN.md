# Phase 2 — Internal IP Management Portal
## Full Technical Specification

---

## Context

The public-facing TTO website (Phase 1) is complete. Phase 2 adds the Internal Intellectual Property Management Portal — a role-gated web application accessible at `/portal/*`.

**Stack:** Laravel 13 (v13.25.0) · PHP 8.4 · Tailwind CSS v4 (via Vite) · Alpine.js · MySQL  
**Auth:** Laravel Breeze (Blade stack) — installed via `composer require laravel/breeze --dev` + `php artisan breeze:install blade`  
**New composer packages:** `laravel/breeze` (dev) · `barryvdh/laravel-dompdf`

---

## 1. Database Migrations

### 1a. `add_portal_fields_to_users_table`
Adds to the existing `users` table:
```
role        ENUM('student','faculty','staff','reviewer','tto_officer','legal_officer','director','system_admin')  DEFAULT 'student'
department  VARCHAR(255) NULLABLE
designation VARCHAR(255) NULLABLE
phone       VARCHAR(50)  NULLABLE
is_active   BOOLEAN      DEFAULT TRUE
```

### 1b. `create_disclosures_table`
```
id                   BIGINT PK AUTO_INCREMENT
disclosure_id        VARCHAR(20) UNIQUE  -- format: DISC-YYYY-NNNN, generated on submit
title                VARCHAR(500)
abstract             TEXT
description          TEXT                -- detailed description
technical_field      VARCHAR(255)
problem_solved       TEXT
novel_features       TEXT
potential_applications TEXT
industry_sector      VARCHAR(255)
existing_alternatives TEXT NULLABLE
funding_source       VARCHAR(255) NULLABLE
sponsor_info         TEXT NULLABLE
project_reference    VARCHAR(255) NULLABLE
status               ENUM('draft','submitted','under_review','ownership_determined',
                          'patentability_assessed','committee_review','approved',
                          'rejected','patent_filing','commercializing')  DEFAULT 'draft'
submitted_by         FK → users.id
assigned_to          FK → users.id  NULLABLE
reviewer_notes       TEXT NULLABLE
rejection_reason     TEXT NULLABLE
submitted_at         TIMESTAMP NULLABLE
timestamps
```

### 1c. `create_disclosure_inventors_table`
```
id             BIGINT PK
disclosure_id  FK → disclosures.id  ON DELETE CASCADE
user_id        FK → users.id  NULLABLE  -- null if external/non-portal inventor
name           VARCHAR(255)
email          VARCHAR(255)
department     VARCHAR(255) NULLABLE
designation    VARCHAR(255) NULLABLE
is_primary     BOOLEAN DEFAULT FALSE
timestamps
```

### 1d. `create_disclosure_documents_table`
```
id             BIGINT PK
disclosure_id  FK → disclosures.id  ON DELETE CASCADE
uploaded_by    FK → users.id
filename       VARCHAR(255)   -- stored filename (uuid-based)
original_name  VARCHAR(255)   -- original upload name
mime_type      VARCHAR(100)
file_size      BIGINT         -- bytes
path           VARCHAR(500)   -- relative to storage/app/private/
document_type  ENUM('disclosure_form','drawing','supporting_data','other')  DEFAULT 'other'
timestamps
```

### 1e. `create_patents_table`
```
id               BIGINT PK
disclosure_id    FK → disclosures.id  NULLABLE
title            VARCHAR(500)
patent_number    VARCHAR(100) NULLABLE
status           ENUM('draft','filed','published','examination','granted','expired','abandoned')  DEFAULT 'draft'
jurisdiction     VARCHAR(10) DEFAULT 'BD'   -- ISO country code
filing_date      DATE NULLABLE
publication_date DATE NULLABLE
grant_date       DATE NULLABLE
expiry_date      DATE NULLABLE
applicant        VARCHAR(255)
attorney_firm    VARCHAR(255) NULLABLE
attorney_contact VARCHAR(255) NULLABLE
notes            TEXT NULLABLE
managed_by       FK → users.id
timestamps
```

### 1f. `create_patent_deadlines_table`
```
id             BIGINT PK
patent_id      FK → patents.id  ON DELETE CASCADE
deadline_type  VARCHAR(255)   -- e.g. "Response to Office Action", "Annual Renewal Fee"
due_date       DATE
is_completed   BOOLEAN DEFAULT FALSE
completed_at   DATE NULLABLE
notes          TEXT NULLABLE
timestamps
```

### 1g. `create_ip_assignments_table`
```
id                  BIGINT PK
disclosure_id       FK → disclosures.id
outcome             ENUM('university','inventor','joint','sponsored_research')
determination_date  DATE NULLABLE
determined_by       FK → users.id
notes               TEXT NULLABLE
timestamps
```

### 1h. `create_agreements_table`
```
id             BIGINT PK
title          VARCHAR(500)
type           ENUM('assignment','nda_cda','revenue_sharing','sponsored_research','licensing','other')
disclosure_id  FK → disclosures.id  NULLABLE
patent_id      FK → patents.id  NULLABLE
parties        JSON    -- array of party names/orgs
signed_date    DATE NULLABLE
expiry_date    DATE NULLABLE
status         ENUM('draft','under_review','signed','expired','terminated')  DEFAULT 'draft'
document_path  VARCHAR(500) NULLABLE  -- uploaded signed agreement file
managed_by     FK → users.id
timestamps
```

### 1i. `create_commercializations_table`
```
id             BIGINT PK
patent_id      FK → patents.id  NULLABLE
disclosure_id  FK → disclosures.id  NULLABLE
title          VARCHAR(500)
type           ENUM('licensing','startup','joint_development','direct_sale')
status         ENUM('evaluation','industry_engagement','negotiation',
                    'agreement_executed','active','closed')  DEFAULT 'evaluation'
partner_name   VARCHAR(255) NULLABLE
partner_contact VARCHAR(255) NULLABLE
partner_email  VARCHAR(255) NULLABLE
description    TEXT NULLABLE
notes          TEXT NULLABLE
managed_by     FK → users.id
timestamps
```

### 1j. `create_revenue_records_table`
```
id             BIGINT PK
source_type    ENUM('licensing','royalty','milestone','other')
agreement_id   FK → agreements.id  NULLABLE
disclosure_id  FK → disclosures.id  NULLABLE
patent_id      FK → patents.id  NULLABLE
gross_amount   DECIMAL(14,2)
deductions     DECIMAL(14,2) DEFAULT 0
net_amount     DECIMAL(14,2)   -- computed: gross - deductions
received_date  DATE
currency       VARCHAR(10) DEFAULT 'BDT'
notes          TEXT NULLABLE
recorded_by    FK → users.id
timestamps
```

### 1k. `create_revenue_distributions_table`
```
id                BIGINT PK
revenue_record_id FK → revenue_records.id  ON DELETE CASCADE
recipient_type    ENUM('inventor','department','university')
recipient_name    VARCHAR(255)
recipient_user_id FK → users.id  NULLABLE
percentage        DECIMAL(5,2)
amount            DECIMAL(14,2)   -- net_amount × (percentage/100)
payment_status    ENUM('pending','paid')  DEFAULT 'pending'
paid_at           DATE NULLABLE
timestamps
```

---

## 2. Models

### `User.php` (extend existing)
Add to `$fillable`: `role`, `department`, `designation`, `phone`, `is_active`  
Add cast: `is_active` → boolean

**New methods:**
```php
public function hasRole(string ...$roles): bool
    // return in_array($this->role, $roles)

public function isSystemAdmin(): bool
    // return $this->role === 'system_admin'

public function isTTOStaff(): bool
    // roles: tto_officer, legal_officer, director, system_admin
    // NOTE: reviewer is intentionally excluded — use canReviewDisclosures() when reviewers need access

public function canReviewDisclosures(): bool
    // roles: reviewer, tto_officer, legal_officer, director, system_admin

public function canManagePatents(): bool
    // roles: tto_officer, legal_officer, director, system_admin

public function disclosures(): HasMany
    // HasMany Disclosure where submitted_by = id

public function getRoleLabelAttribute(): string
    // maps role slug → human label e.g. 'tto_officer' → 'TTO Officer'

public function getRoleBadgeClassAttribute(): string
    // maps role → CSS class for badge color
```

### `Disclosure.php` (new)
```php
$fillable = ['disclosure_id','title','abstract','description','technical_field',
             'problem_solved','novel_features','potential_applications','industry_sector',
             'existing_alternatives','funding_source','sponsor_info','project_reference',
             'status','submitted_by','assigned_to','reviewer_notes','rejection_reason','submitted_at']

$casts = ['submitted_at' => 'datetime']

// Relationships
public function submitter(): BelongsTo     // → User (submitted_by)
public function assignee(): BelongsTo      // → User (assigned_to)
public function inventors(): HasMany       // → DisclosureInventor
public function documents(): HasMany       // → DisclosureDocument
public function patent(): HasOne           // → Patent
public function assignment(): HasOne       // → IpAssignment
public function commercialization(): HasOne // → Commercialization

// Scopes
public function scopeForUser(Builder $q, User $user): Builder
    // if user canReviewDisclosures() → no filter; else → where submitted_by = user.id
    // (uses canReviewDisclosures, not isTTOStaff — so reviewer role also sees all disclosures)

public function scopeByStatus(Builder $q, string $status): Builder
    // where status = $status

// Accessors
public function getStatusLabelAttribute(): string
    // maps status slug → human label
public function getStatusBadgeClassAttribute(): string
    // maps status → CSS badge class (color-coded)

// Static
public static function generateDisclosureId(): string
    // DISC-{year}-{4-digit sequence, padded, auto-incremented from max for that year}
    // e.g. DISC-2026-0001
    // Implementation: query MAX disclosure_id LIKE 'DISC-YYYY-%', parse last 4 digits, increment
    // The UNIQUE constraint on disclosure_id protects data integrity if two requests race;
    // wrap DisclosureController::submit() in a DB transaction to get a clean error rather than 500
```

### `DisclosureInventor.php` (new)
```php
$fillable = ['disclosure_id','user_id','name','email','department','designation','is_primary']
$casts = ['is_primary' => 'boolean']
public function disclosure(): BelongsTo
public function user(): BelongsTo
```

### `DisclosureDocument.php` (new)
```php
$fillable = ['disclosure_id','uploaded_by','filename','original_name','mime_type','file_size','path','document_type']
public function disclosure(): BelongsTo
public function uploader(): BelongsTo   // → User (uploaded_by)
public function getDownloadUrlAttribute(): string
    // returns signed storage URL via Storage::temporaryUrl or route('portal.documents.download', $this)
```

### `Patent.php` (new)
```php
$fillable = ['disclosure_id','title','patent_number','status','jurisdiction','filing_date',
             'publication_date','grant_date','expiry_date','applicant','attorney_firm',
             'attorney_contact','notes','managed_by']
$casts = ['filing_date'=>'date','publication_date'=>'date','grant_date'=>'date','expiry_date'=>'date']
public function disclosure(): BelongsTo
public function manager(): BelongsTo       // → User (managed_by)
public function deadlines(): HasMany       // → PatentDeadline
public function agreements(): HasMany      // → Agreement
public function commercialization(): HasOne

public function getStatusLabelAttribute(): string
public function getStatusBadgeClassAttribute(): string
public function getIsExpiringSoonAttribute(): bool
    // expiry_date within 90 days and status = granted
```

### `PatentDeadline.php` (new)
```php
$fillable = ['patent_id','deadline_type','due_date','is_completed','completed_at','notes']
$casts = ['due_date'=>'date','is_completed'=>'boolean','completed_at'=>'date']
public function patent(): BelongsTo
public function getIsOverdueAttribute(): bool
    // due_date < today && !is_completed
```

### `IpAssignment.php` (new)
```php
$fillable = ['disclosure_id','outcome','determination_date','determined_by','notes']
$casts = ['determination_date'=>'date']
public function disclosure(): BelongsTo
public function determinedBy(): BelongsTo  // → User
public function getOutcomeLabelAttribute(): string
```

### `Agreement.php` (new)
```php
$fillable = ['title','type','disclosure_id','patent_id','parties','signed_date',
             'expiry_date','status','document_path','managed_by']
$casts = ['parties'=>'array','signed_date'=>'date','expiry_date'=>'date']
public function disclosure(): BelongsTo
public function patent(): BelongsTo
public function manager(): BelongsTo       // → User
public function getTypeLabelAttribute(): string
public function getStatusLabelAttribute(): string
```

### `Commercialization.php` (new)
```php
$fillable = ['patent_id','disclosure_id','title','type','status','partner_name',
             'partner_contact','partner_email','description','notes','managed_by']
public function patent(): BelongsTo
public function disclosure(): BelongsTo
public function manager(): BelongsTo
public function getTypeLabelAttribute(): string
public function getStatusLabelAttribute(): string
```

### `RevenueRecord.php` (new)
```php
$fillable = ['source_type','agreement_id','disclosure_id','patent_id',
             'gross_amount','deductions','net_amount','received_date','currency','notes','recorded_by']
$casts = ['received_date'=>'date','gross_amount'=>'decimal:2','deductions'=>'decimal:2','net_amount'=>'decimal:2']
public function agreement(): BelongsTo
public function disclosure(): BelongsTo
public function patent(): BelongsTo
public function recorder(): BelongsTo      // → User (recorded_by)
public function distributions(): HasMany   // → RevenueDistribution

// Boot: auto-compute net_amount = gross_amount - deductions on saving
protected static function booted(): void
    // static::saving(fn($r) => $r->net_amount = $r->gross_amount - $r->deductions)
```

### `RevenueDistribution.php` (new)
```php
$fillable = ['revenue_record_id','recipient_type','recipient_name','recipient_user_id',
             'percentage','amount','payment_status','paid_at']
$casts = ['percentage'=>'decimal:2','amount'=>'decimal:2','paid_at'=>'date']
public function revenueRecord(): BelongsTo
public function recipientUser(): BelongsTo  // → User
```

---

## 3. Breeze Auth Integration

Run once to scaffold auth (do this BEFORE writing any custom migrations):
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```
**Do NOT run `php artisan migrate` yet** — wait until all custom migrations in Section 1 are written, then run once to apply everything together.

Breeze generates: `resources/views/auth/` (login, register, forgot-password, reset-password, confirm-password, verify-email), `app/Http/Controllers/Auth/` (all auth controllers), password reset email views, and adds `auth` middleware to `bootstrap/app.php`.

**What we keep from Breeze as-is:** login, register, forgot/reset password, email verification — all at `/login`, `/register`, `/forgot-password`, `/reset-password/{token}`.

**What we change after install:**
- `routes/auth.php` — Breeze creates this; leave it untouched
- `app/Http/Controllers/Auth/RegisteredUserController.php` — in `store()`, three changes:
  1. Add validation rules for optional fields: `'department' => 'nullable|string|max:255', 'designation' => 'nullable|string|max:255', 'phone' => 'nullable|string|max:50'`
  2. Add to `User::create()`: `'role' => 'student', 'is_active' => true, 'email_verified_at' => now()`
  3. Add optional fields to `User::create()`: `'department' => $request->department, 'designation' => $request->designation, 'phone' => $request->phone`
  *(If TTO wants email verification enforced, remove `email_verified_at => now()` from this controller only — keep it in the seeder)*
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` — in `store()`, two changes:
  1. After `$request->authenticate()`, add `is_active` check:
     ```php
     if (!Auth::user()->is_active) {
         Auth::logout();
         throw ValidationException::withMessages(['email' => 'Your account has been deactivated.']);
     }
     ```
  2. Change the redirect from `route('dashboard')` → `route('portal.dashboard')`:
     ```php
     return redirect()->intended(route('portal.dashboard', absolute: false));
     ```
- Portal layout sidebar "logout" button uses Breeze's existing `POST /logout` route (`route('logout')`)

## 4. Middleware

### `app/Http/Middleware/CheckRole.php`
```php
// Handle: receives comma-separated allowed roles from route definition
// e.g. Route::middleware('role:tto_officer,director')

public function handle(Request $request, Closure $next, string ...$roles): Response
    // 1. if !Auth::check() → redirect to route('login')   [Breeze's route name]
    // 2. if !Auth::user()->is_active → Auth::logout(); $request->session()->invalidate();
    //       redirect route('login') with error 'Your account has been deactivated.'
    // 3. if !Auth::user()->hasRole(...$roles) → abort(403)
    // 4. return $next($request)
```

Register in `bootstrap/app.php`:
```php
// In withRouting(), Breeze adds routes/auth.php automatically on install.
// After install it will look like:
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
// Note: routes/auth.php is included via require in routes/web.php by Breeze, not via withRouting().

// Add CheckRole middleware alias in withMiddleware():
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias(['role' => \App\Http\Middleware\CheckRole::class]);
})
```

**`is_active` enforcement + redirect-after-login** are both handled in `AuthenticatedSessionController` as described in Section 3.

---

## 5. Routes (`routes/web.php` additions)

Auth routes are handled by `routes/auth.php` (generated by Breeze — do not touch).

Portal routes added to `routes/web.php`:
```php
// ─── Portal (auth provided by Breeze's routes/auth.php) ──────────
Route::prefix('portal')->name('portal.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Disclosures
    Route::resource('disclosures', DisclosureController::class)->except(['destroy']);
    Route::post('disclosures/{disclosure}/submit', [DisclosureController::class, 'submit'])->name('disclosures.submit');
    Route::post('disclosures/{disclosure}/status', [DisclosureController::class, 'updateStatus'])
        ->middleware('role:reviewer,tto_officer,legal_officer,director,system_admin')->name('disclosures.status');
    Route::post('disclosures/{disclosure}/assign', [DisclosureController::class, 'assign'])
        ->middleware('role:tto_officer,director,system_admin')->name('disclosures.assign');

    // Document download (signed, role-gated)
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Patents
    Route::resource('patents', PatentController::class)->except(['destroy'])
        ->middleware('role:tto_officer,legal_officer,director,system_admin');
    Route::resource('patents.deadlines', PatentDeadlineController::class)->only(['store','update','destroy'])
        ->middleware('role:tto_officer,legal_officer,director,system_admin')
        ->scopedBindings();  // ensures {deadline} must belong to {patent}, prevents cross-patent access

    // IP Assignments
    Route::resource('assignments', AssignmentController::class)->only(['index','show','create','store'])
        ->middleware('role:tto_officer,legal_officer,director,system_admin');

    // Agreements
    Route::resource('agreements', AgreementController::class)->except(['destroy'])
        ->middleware('role:tto_officer,legal_officer,director,system_admin');
    Route::get('agreements/{agreement}/download', [AgreementController::class, 'download'])
        ->middleware('role:tto_officer,legal_officer,director,system_admin')->name('agreements.download');

    // Commercialization
    Route::resource('commercialization', CommercializationController::class)->except(['destroy'])
        ->middleware('role:tto_officer,director,system_admin');

    // Revenue
    Route::resource('revenue', RevenueController::class)->only(['index','show','create','store'])
        ->middleware('role:tto_officer,director,system_admin');
    Route::post('revenue/{revenue}/distributions/{distribution}/mark-paid', [RevenueController::class, 'markPaid'])
        ->middleware('role:tto_officer,director,system_admin')->name('revenue.distributions.mark-paid');

    // Reports
    Route::prefix('reports')->name('reports.')->middleware('role:tto_officer,director,system_admin')->group(function () {
        Route::get('/',                 [ReportController::class, 'index'])->name('index');
        Route::get('/disclosures',      [ReportController::class, 'disclosures'])->name('disclosures');
        Route::get('/patents',          [ReportController::class, 'patents'])->name('patents');
        Route::get('/revenue',          [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/commercialization',[ReportController::class, 'commercialization'])->name('commercialization');
        Route::get('/export/{type}',    [ReportController::class, 'export'])->name('export');
    });

    // Admin — user role management (system_admin only)
    Route::prefix('admin')->name('admin.')->middleware('role:system_admin')->group(function () {
        Route::resource('users', Admin\UserController::class)->only(['index','edit','update']);
    });

    // Admin — Technology & Startup content management
    Route::prefix('admin')->name('admin.')->middleware('role:tto_officer,system_admin')->group(function () {
        Route::resource('technologies', Admin\TechnologyController::class)->except(['show']);
        Route::resource('startups', Admin\StartupController::class)->except(['show']);
    });
});

// Update the existing /portal redirect to go to dashboard (Breeze handles /login):
// Route::redirect('/portal', '/portal/dashboard');
```

---

## 6. Controllers

All portal controllers live in `app/Http/Controllers/Portal/`.  
Breeze auth controllers in `app/Http/Controllers/Auth/` are **not touched** except `RegisteredUserController` (one line).

---

### `DashboardController.php`
```
index(Request $request): View
    $user = Auth::user()

    // Stats — scoped by role
    // Use canReviewDisclosures() (not isTTOStaff()) so reviewers get the TTO-scoped stats view,
    // consistent with scopeForUser() which also uses canReviewDisclosures()
    $stats = []
    if ($user->canReviewDisclosures()):
        $stats['total_disclosures']     = Disclosure::count()
        $stats['pending_review']        = Disclosure::byStatus('submitted')->count()
        $stats['active_patents']        = Patent::whereIn('status',['filed','examination','granted'])->count()
        $stats['total_revenue']         = RevenueRecord::sum('net_amount')
    else:
        $stats['my_disclosures']        = Disclosure::where('submitted_by', $user->id)->count()
        $stats['my_drafts']             = Disclosure::where('submitted_by',$user->id)->byStatus('draft')->count()

    // Recent disclosures (role-scoped)
    $recentDisclosures = Disclosure::scopeForUser(query, $user)
        ->with('submitter','inventors')
        ->latest()->limit(5)->get()

    // Upcoming patent deadlines (TTO staff only)
    $upcomingDeadlines = $user->isTTOStaff()
        ? PatentDeadline::with('patent')
            ->where('is_completed', false)
            ->where('due_date', '<=', now()->addDays(90))
            ->orderBy('due_date')->limit(5)->get()
        : collect()

    return view('portal.dashboard', compact('stats','recentDisclosures','upcomingDeadlines','user'))
```

---

### `DisclosureController.php`
```
index(Request $request): View
    $user = Auth::user()
    $query = Disclosure::scopeForUser(Disclosure::query(), $user)->with('submitter','inventors')
    if ($request->status) $query->byStatus($request->status)
    if ($request->search) $query->where('title','like',"%{$request->search}%")
    $disclosures = $query->latest()->paginate(15)
    return view('portal.disclosures.index', compact('disclosures'))

create(): View
    return view('portal.disclosures.create')

store(Request $request): RedirectResponse
    validate:
        title               required|string|max:500
        abstract            required|string
        description         required|string
        technical_field     required|string|max:255
        problem_solved      required|string
        novel_features      required|string
        potential_applications required|string
        industry_sector     required|string|max:255
        existing_alternatives nullable|string
        funding_source      nullable|string|max:255
        sponsor_info        nullable|string
        project_reference   nullable|string|max:255
        inventors           required|array|min:1
        inventors.*.name    required|string|max:255
        inventors.*.email   required|email
        inventors.*.department nullable|string
        inventors.*.designation nullable|string
        documents.*         nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240

    // Strip inventors + documents (handled separately) from validated data
    $data = $request->safe()->except(['inventors', 'documents'])
    $disclosure = Disclosure::create([
        ...$data,
        'submitted_by' => Auth::id(),
        'status' => 'draft'
        // note: disclosure_id NOT set yet — only set when submitted
    ])

    // Save inventors
    foreach ($request->inventors as $i => $inv):
        $disclosure->inventors()->create([...$inv, 'is_primary' => ($i === 0)])

    // Handle file uploads
    foreach ($request->file('documents', []) as $file):
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension()
        $path = $file->storeAs("disclosures/{$disclosure->id}", $filename, 'private')
        $disclosure->documents()->create([
            'uploaded_by'   => Auth::id(),
            'filename'      => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'file_size'     => $file->getSize(),
            'path'          => $path,
        ])

    redirect route('portal.disclosures.show', $disclosure)
        ->with('success', 'Disclosure saved as draft.')

show(Disclosure $disclosure): View
    // authorize: submitter or TTO staff
    $this->authorizeView($disclosure)
    $disclosure->load('submitter','inventors','documents.uploader','patent','assignment','assignee')
    return view('portal.disclosures.show', compact('disclosure'))
    // view includes: status timeline, inventor list, documents, TTO action panel

edit(Disclosure $disclosure): View
    // authorize: only submitter, only if status = draft
    abort_unless(Auth::id() === $disclosure->submitted_by && $disclosure->status === 'draft', 403)
    return view('portal.disclosures.edit', compact('disclosure'))

update(Request $request, Disclosure $disclosure): RedirectResponse
    // same validation as store, update fields, re-save inventors (delete + recreate)
    abort_unless(Auth::id() === $disclosure->submitted_by && $disclosure->status === 'draft', 403)
    [validate & update]
    redirect route('portal.disclosures.show', $disclosure)->with('success','Draft updated.')

submit(Request $request, Disclosure $disclosure): RedirectResponse
    // Changes status draft → submitted, assigns disclosure_id
    abort_unless(Auth::id() === $disclosure->submitted_by && $disclosure->status === 'draft', 403)
    $disclosure->update([
        'status'         => 'submitted',
        'disclosure_id'  => Disclosure::generateDisclosureId(),
        'submitted_at'   => now(),
    ])
    redirect route('portal.disclosures.show', $disclosure)
        ->with('success', "Disclosure submitted. Your ID is {$disclosure->disclosure_id}.")

updateStatus(Request $request, Disclosure $disclosure): RedirectResponse
    // TTO staff update status + optional notes
    validate: status required|in:[all statuses], reviewer_notes nullable|string, rejection_reason nullable|string
    $disclosure->update($request->only('status','reviewer_notes','rejection_reason'))
    redirect back()->with('success','Status updated.')

assign(Request $request, Disclosure $disclosure): RedirectResponse
    // Assign a reviewer/officer to the disclosure
    validate: assigned_to required|exists:users,id
    $disclosure->update(['assigned_to' => $request->assigned_to])
    redirect back()->with('success','Assignee updated.')

// Private helper
private function authorizeView(Disclosure $disclosure): void
    $user = Auth::user()
    // canReviewDisclosures() includes reviewer role (isTTOStaff does not)
    abort_unless($user->canReviewDisclosures() || $disclosure->submitted_by === $user->id, 403)
```

---

### `DocumentController.php`
```
download(DisclosureDocument $document): StreamedResponse
    // Authorize: submitter or TTO staff
    $user = Auth::user()
    $disclosure = $document->disclosure
    abort_unless($user->isTTOStaff() || $disclosure->submitted_by === $user->id, 403)
    abort_unless(Storage::disk('private')->exists($document->path), 404)
    return Storage::disk('private')->download($document->path, $document->original_name)
```

---

### `PatentController.php`
```
index(Request $request): View
    $patents = Patent::with('disclosure','manager')
        ->when($request->status, fn($q) => $q->where('status',$request->status))
        ->when($request->jurisdiction, fn($q) => $q->where('jurisdiction',$request->jurisdiction))
        ->latest()->paginate(15)
    $expiringCount = Patent::where('status','granted')
        ->where('expiry_date','<=',now()->addDays(90))->count()
    return view('portal.patents.index', compact('patents','expiringCount'))

create(): View
    $disclosures = Disclosure::where('status','approved')
        ->whereDoesntHave('patent')->get(['id','title','disclosure_id'])
    return view('portal.patents.create', compact('disclosures'))

store(Request $request): RedirectResponse
    validate:
        disclosure_id    nullable|exists:disclosures,id
        title            required|string|max:500
        patent_number    nullable|string|max:100
        status           required|in:[enum values]
        jurisdiction     required|string|max:10
        filing_date      nullable|date
        publication_date nullable|date
        grant_date       nullable|date
        expiry_date      nullable|date
        applicant        required|string|max:255
        attorney_firm    nullable|string|max:255
        attorney_contact nullable|string|max:255
        notes            nullable|string
    Patent::create([...$validated, 'managed_by'=>Auth::id()])
    redirect route('portal.patents.index')->with('success','Patent record created.')

show(Patent $patent): View
    $patent->load('disclosure','manager','deadlines','agreements','commercialization')
    return view('portal.patents.show', compact('patent'))

edit(Patent $patent): View
    $disclosures = Disclosure::where('status','approved')->get(['id','title','disclosure_id'])
    return view('portal.patents.edit', compact('patent','disclosures'))

update(Request $request, Patent $patent): RedirectResponse
    [same validation as store]
    $patent->update($request->validated())
    redirect route('portal.patents.show', $patent)->with('success','Patent updated.')
```

### `PatentDeadlineController.php`
```
store(Request $request, Patent $patent): RedirectResponse
    validate: deadline_type required|string, due_date required|date, notes nullable|string
    $patent->deadlines()->create($request->validated())
    redirect back()->with('success','Deadline added.')

update(Request $request, Patent $patent, PatentDeadline $deadline): RedirectResponse
    validate: is_completed required|boolean, completed_at nullable|date, notes nullable|string
    $deadline->update($request->only('is_completed','completed_at','notes'))
    redirect back()->with('success','Deadline updated.')

destroy(Patent $patent, PatentDeadline $deadline): RedirectResponse
    $deadline->delete()
    redirect back()->with('success','Deadline removed.')
```

---

### `AssignmentController.php`
```
index(Request $request): View
    $assignments = IpAssignment::with('disclosure','determinedBy')->latest()->paginate(15)
    return view('portal.assignments.index', compact('assignments'))

show(IpAssignment $assignment): View
    $assignment->load('disclosure.inventors','determinedBy')
    return view('portal.assignments.show', compact('assignment'))

create(): View
    // List disclosures that don't have an assignment yet, status = submitted or under_review+
    $disclosures = Disclosure::whereDoesntHave('assignment')
        ->whereNotIn('status',['draft'])->get(['id','title','disclosure_id'])
    return view('portal.assignments.create', compact('disclosures'))

store(Request $request): RedirectResponse
    validate:
        disclosure_id        required|exists:disclosures,id|unique:ip_assignments,disclosure_id
        outcome              required|in:university,inventor,joint,sponsored_research
        determination_date   required|date
        notes                nullable|string
    IpAssignment::create([...$validated, 'determined_by'=>Auth::id()])
    // Also update disclosure status to ownership_determined
    Disclosure::find($request->disclosure_id)
        ->update(['status'=>'ownership_determined'])
    redirect route('portal.assignments.index')->with('success','Ownership determination recorded.')
```

---

### `AgreementController.php`
```
index(Request $request): View
    $agreements = Agreement::with('disclosure','patent','manager')
        ->when($request->type, fn($q)=>$q->where('type',$request->type))
        ->latest()->paginate(15)
    return view('portal.agreements.index', compact('agreements'))

create(): View
    $disclosures = Disclosure::whereIn('status',['approved','commercializing'])->get(['id','title','disclosure_id'])
    $patents = Patent::all(['id','title','patent_number'])
    return view('portal.agreements.create', compact('disclosures','patents'))

store(Request $request): RedirectResponse
    validate:
        title          required|string|max:500
        type           required|in:[enum values]
        disclosure_id  nullable|exists:disclosures,id
        patent_id      nullable|exists:patents,id
        parties        required|array|min:1
        parties.*      required|string
        signed_date    nullable|date
        expiry_date    nullable|date
        status         required|in:[enum values]
        document       nullable|file|mimes:pdf,doc,docx|max:20480
    // Exclude uploaded file from create() — same pattern as Disclosure/Revenue controllers
    $data = $request->safe()->except(['document'])
    if ($request->hasFile('document')):
        $path = $request->file('document')->store('agreements','private')
        $data['document_path'] = $path
    $data['managed_by'] = Auth::id()
    Agreement::create($data)
    redirect route('portal.agreements.index')->with('success','Agreement created.')

show(Agreement $agreement): View
    return view('portal.agreements.show', compact('agreement'))

edit(Agreement $agreement): View
    return view('portal.agreements.edit', compact('agreement'))

update(Request $request, Agreement $agreement): RedirectResponse
    [same as store validation, handle new document upload if provided]
    $agreement->update($data)
    redirect route('portal.agreements.show', $agreement)->with('success','Agreement updated.')

download(Agreement $agreement): StreamedResponse
    abort_unless($agreement->document_path && Storage::disk('private')->exists($agreement->document_path), 404)
    return Storage::disk('private')->download($agreement->document_path)
```

---

### `CommercializationController.php`
```
index(Request $request): View
    $records = Commercialization::with('patent','disclosure','manager')
        ->when($request->type,   fn($q)=>$q->where('type',$request->type))
        ->when($request->status, fn($q)=>$q->where('status',$request->status))
        ->latest()->paginate(15)
    return view('portal.commercialization.index', compact('records'))

create(): View
    $patents = Patent::whereIn('status',['filed','granted'])->get(['id','title','patent_number'])
    $disclosures = Disclosure::whereIn('status',['approved','commercializing'])->get(['id','title','disclosure_id'])
    return view('portal.commercialization.create', compact('patents','disclosures'))

store(Request $request): RedirectResponse
    validate:
        patent_id        nullable|exists:patents,id
        disclosure_id    nullable|exists:disclosures,id
        title            required|string|max:500
        type             required|in:[enum values]
        status           required|in:[enum values]
        partner_name     nullable|string|max:255
        partner_contact  nullable|string|max:255
        partner_email    nullable|email
        description      nullable|string
        notes            nullable|string
    Commercialization::create([...$validated, 'managed_by'=>Auth::id()])
    redirect route('portal.commercialization.index')->with('success','Record created.')

show(Commercialization $commercialization): View
    $commercialization->load('patent','disclosure','manager')
    return view('portal.commercialization.show', compact('commercialization'))

edit / update — same pattern as create/store
```

---

### `RevenueController.php`
```
index(Request $request): View
    $records = RevenueRecord::with('disclosure','patent','recorder','distributions')
        ->latest('received_date')->paginate(15)
    $totalNet = RevenueRecord::sum('net_amount')
    $pendingDistributions = RevenueDistribution::where('payment_status','pending')->sum('amount')
    return view('portal.revenue.index', compact('records','totalNet','pendingDistributions'))

show(RevenueRecord $revenue): View
    $revenue->load('distributions.recipientUser','agreement','disclosure','patent')
    return view('portal.revenue.show', compact('revenue'))

create(): View
    $disclosures = Disclosure::all(['id','title','disclosure_id'])
    $patents     = Patent::all(['id','title','patent_number'])
    $agreements  = Agreement::where('status','signed')->get(['id','title'])
    return view('portal.revenue.create', compact('disclosures','patents','agreements'))

store(Request $request): RedirectResponse
    validate:
        source_type      required|in:[enum values]
        agreement_id     nullable|exists:agreements,id
        disclosure_id    nullable|exists:disclosures,id
        patent_id        nullable|exists:patents,id
        gross_amount     required|numeric|min:0
        deductions       required|numeric|min:0
        received_date    required|date
        currency         required|string|max:10
        notes            nullable|string
        distributions              required|array|min:1
        distributions.*.recipient_type  required|in:inventor,department,university
        distributions.*.recipient_name  required|string|max:255
        distributions.*.recipient_user_id nullable|exists:users,id
        distributions.*.percentage       required|numeric|min:0|max:100

    // Validate percentages sum to 100
    $total = collect($request->distributions)->sum('percentage')
    if ($total != 100): back()->withErrors(['distributions'=>'Percentages must sum to 100%'])

    $net = $request->gross_amount - $request->deductions
    // Exclude nested distributions array from create() — same pattern as DisclosureController::store()
    $data = $request->safe()->except(['distributions'])
    $record = RevenueRecord::create([...$data, 'net_amount'=>$net, 'recorded_by'=>Auth::id()])

    foreach ($request->distributions as $dist):
        $record->distributions()->create([
            ...$dist,
            'amount' => round($net * ($dist['percentage'] / 100), 2)
        ])

    redirect route('portal.revenue.show', $record)->with('success','Revenue recorded.')

markPaid(Request $request, RevenueRecord $revenue, RevenueDistribution $distribution): RedirectResponse
    validate: paid_at required|date
    $distribution->update(['payment_status'=>'paid','paid_at'=>$request->paid_at])
    redirect back()->with('success','Marked as paid.')
```

---

### `ReportController.php`
```
index(): View
    return view('portal.reports.index')  // report selector cards

disclosures(Request $request): View
    $byStatus = Disclosure::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count','status')
    $bySector = Disclosure::selectRaw('industry_sector, count(*) as count')->groupBy('industry_sector')->pluck('count','industry_sector')
    $monthly  = Disclosure::selectRaw('MONTH(created_at) as month, count(*) as count')
        ->whereYear('created_at', now()->year)->groupBy('month')->pluck('count','month')
    $list = Disclosure::with('submitter','inventors')->latest()->get()
    return view('portal.reports.disclosures', compact('byStatus','bySector','monthly','list'))

patents(Request $request): View
    $byStatus = Patent::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count','status')
    $byJurisdiction = Patent::selectRaw('jurisdiction, count(*) as count')->groupBy('jurisdiction')->pluck('count','jurisdiction')
    $expiringSoon = Patent::where('status','granted')->where('expiry_date','<=',now()->addDays(90))->get()
    return view('portal.reports.patents', compact('byStatus','byJurisdiction','expiringSoon'))

revenue(Request $request): View
    $totalGross = RevenueRecord::sum('gross_amount')
    $totalNet   = RevenueRecord::sum('net_amount')
    $byType     = RevenueRecord::selectRaw('source_type, sum(net_amount) as total')->groupBy('source_type')->pluck('total','source_type')
    $pending    = RevenueDistribution::where('payment_status','pending')->sum('amount')
    $records    = RevenueRecord::with('distributions')->latest('received_date')->get()
    return view('portal.reports.revenue', compact('totalGross','totalNet','byType','pending','records'))

commercialization(Request $request): View
    $byStatus = Commercialization::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count','status')
    $byType   = Commercialization::selectRaw('type, count(*) as count')->groupBy('type')->pluck('count','type')
    $records  = Commercialization::with('patent','disclosure')->latest()->get()
    return view('portal.reports.commercialization', compact('byStatus','byType','records'))

export(Request $request, string $type): StreamedResponse
    // $type: disclosures | patents | revenue | commercialization
    // Build appropriate query, stream CSV via PHP fputcsv
    // Column headers + data rows

    return response()->streamDownload(function() use ($type) {
        $handle = fopen('php://output','w')
        switch ($type):
            case 'disclosures':
                fputcsv($handle, ['ID','Title','Status','Submitted By','Submitted At','Industry Sector'])
                foreach (Disclosure::with('submitter')->get() as $d):
                    fputcsv($handle, [$d->disclosure_id, $d->title, $d->status,
                        $d->submitter->name, $d->submitted_at, $d->industry_sector])
            case 'patents':
                fputcsv($handle, ['Title','Patent Number','Status','Jurisdiction','Filing Date','Expiry Date'])
                foreach (Patent::all() as $p):
                    fputcsv($handle, [$p->title,$p->patent_number,$p->status,
                        $p->jurisdiction,$p->filing_date,$p->expiry_date])
            // etc. for revenue, commercialization
        fclose($handle)
    }, "{$type}-report-".now()->format('Y-m-d').'.csv', ['Content-Type'=>'text/csv'])
```

---

### `Admin/UserController.php`
```
index(Request $request): View
    $users = User::query()
        ->when($request->role, fn($q)=>$q->where('role',$request->role))
        ->when($request->search, fn($q)=>$q->where('name','like',"%{$request->search}%")
            ->orWhere('email','like',"%{$request->search}%"))
        ->paginate(20)
    return view('portal.admin.users.index', compact('users'))

edit(User $user): View
    return view('portal.admin.users.edit', compact('user'))

update(Request $request, User $user): RedirectResponse
    validate:
        role        required|in:[all role values]
        department  nullable|string|max:255
        designation nullable|string|max:255
        is_active   required|boolean
    $user->update($request->validated())
    redirect route('portal.admin.users.index')->with('success','User updated.')
```

### `Admin/TechnologyController.php`
Full CRUD wrapping the existing `Technology` model. Uses `portal.admin.technologies.*` views.
Methods: `index`, `create`, `store`, `edit`, `update`, `destroy` (soft-unpublish: set `is_published = false`, not hard delete).
`Technology` model already has a `published()` scope (used on the home page), confirming `is_published` column exists.

### `Admin/StartupController.php`
Full CRUD wrapping the existing `Startup` model. Same pattern.
**Important:** Check whether `Startup` model has an `is_published` column (the public startups page does not filter by published status). If it doesn't exist, add a migration: `$table->boolean('is_published')->default(true)` on `startups` table, and update the public-facing `StartupController` query to filter `where('is_published', true)`. Without this column, `destroy()` cannot do a soft-unpublish and would need to use `delete()` instead.

---

## 7. Portal Layout — `resources/views/layouts/portal.blade.php`

Structure:
```html
<html>
  <head> [meta, vite assets, same fonts] </head>
  <body class="bg-gray-50">

    <!-- Sidebar (fixed, 240px, hidden on mobile) -->
    <aside id="portal-sidebar" class="fixed inset-y-0 left-0 w-60 bg-tto-teal-900 ...">
      <!-- Logo -->
      <div class="p-5 border-b border-white/10">
        <img src="{{ asset('assets/img/TTO_logo_shortened.png') }}" class="h-10">
      </div>
      <!-- Nav links (role-gated with @if Auth::user()->hasRole(...)) -->
      <nav class="p-3 space-y-1">
        [links with active state via request()->routeIs()]
      </nav>
      <!-- User info + logout at bottom -->
      <div class="absolute bottom-0 ...">
        [user name, role badge, logout button]
      </div>
    </aside>

    <!-- Main (ml-60) -->
    <div class="ml-60 min-h-screen flex flex-col">
      <!-- Top bar -->
      <header class="bg-white border-b border-gray-200 sticky top-0 z-10 px-6 py-4 flex items-center justify-between">
        <h1 class="font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        [mobile hamburger, notifications bell placeholder]
      </header>

      <!-- Content -->
      <main class="flex-1 p-6">
        @if (session('success')) [success alert] @endif
        @if (session('error'))  [error alert]   @endif
        @yield('content')
      </main>
    </div>

    <!-- Mobile sidebar overlay (Alpine x-data) -->
  </body>
</html>
```

Sidebar active link style: `bg-white/10 text-white` on active, `text-white/70 hover:bg-white/5 hover:text-white` on inactive.

---

## 8. Auth Views (Breeze-generated, light customization)

Breeze scaffolds `resources/views/auth/` with login, register, forgot-password, reset-password, confirm-password, verify-email. These render inside Breeze's `guest` layout.

**Customization needed** — only on `register.blade.php`: add optional fields for `department`, `designation`, `phone`, and a note: *"Your account will be created with Student role. A TTO administrator can update your role after registration."*

All other auth views (login, forgot-password, reset-password) are used as-is with no changes.

---

## 9. Seeder — `SystemAdminSeeder.php`

Creates one default admin user:
```php
User::create([
    'name'              => 'System Administrator',
    'email'             => 'admin@tto.northsouth.edu',
    'password'          => Hash::make('Admin@TTO2026'),
    'role'              => 'system_admin',
    'department'        => 'Technology Transfer Office',
    'is_active'         => true,
    'email_verified_at' => now(),   // pre-verify so admin can log in without email verification step
])
```

Add to `DatabaseSeeder::run()`. Print credentials after seeding.

---

## 10. Key Views List

Auth views are at `resources/views/auth/` (Breeze root) — NOT under portal/.

```
resources/views/portal/
  dashboard.blade.php
  disclosures/
    index.blade.php      — paginated table, status filter, search
    create.blade.php     — multi-section form with Alpine.js dynamic inventor rows
    edit.blade.php       — same form, pre-filled
    show.blade.php       — detail view: info, inventors, docs, status timeline, TTO panel
  patents/
    index.blade.php      — table with status badges, jurisdiction filter, "expiring soon" banner
    create.blade.php
    edit.blade.php
    show.blade.php       — detail + deadlines list + linked agreements
  assignments/
    index.blade.php
    create.blade.php
    show.blade.php
  agreements/
    index.blade.php
    create.blade.php
    edit.blade.php
    show.blade.php
  commercialization/
    index.blade.php      — kanban-style status columns using Alpine
    create.blade.php
    edit.blade.php
    show.blade.php
  revenue/
    index.blade.php      — summary cards + records table
    create.blade.php     — gross/deductions fields + dynamic distribution rows (Alpine)
    show.blade.php       — distributions table with "Mark Paid" buttons
  reports/
    index.blade.php      — report selector cards
    disclosures.blade.php — charts (inline SVG) + filterable table + CSV export button
    patents.blade.php
    revenue.blade.php
    commercialization.blade.php
  admin/
    users/
      index.blade.php   — users table with role badges, active/inactive toggle
      edit.blade.php    — role, department, designation, is_active fields
    technologies/
      index.blade.php
      create.blade.php
      edit.blade.php
    startups/
      index.blade.php
      create.blade.php
      edit.blade.php
```

---

## 11. Composer & Config Changes

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require barryvdh/laravel-dompdf
```

`config/filesystems.php` — add `private` disk:
```php
'private' => [
    'driver' => 'local',
    'root'   => storage_path('app/private'),
    'visibility' => 'private',
],
```

`bootstrap/app.php` — register `CheckRole` middleware alias as `role`.

`AppServiceProvider.php` — already has HTTPS fix from responsive commit (no change needed).

---

## 12. Verification Steps

1. `composer require laravel/breeze --dev && php artisan breeze:install blade && npm run build` — no errors, auth views scaffolded
2. `composer require barryvdh/laravel-dompdf` — no errors
3. `php artisan migrate` — 11 new tables created (Breeze adds `password_reset_tokens` + `sessions` if not present)
4. `php artisan db:seed --class=SystemAdminSeeder` — admin user created, credentials printed
5. Visit `/login` (Breeze route) — standard login form renders
6. Login as `admin@tto.northsouth.edu` / `Admin@TTO2026` → redirected to `/portal/dashboard`, all sidebar items visible
7. Register a new account at `/register` → role defaults to `student`, sidebar shows only "Dashboard" + "My Disclosures"
8. As student: create disclosure → draft saved; submit → `DISC-2026-0001` assigned, status = submitted
9. As admin: update user role to `tto_officer` → user can now access patents, assignments, agreements
10. Deactivate a user account via admin panel → that user cannot log in, sees "account deactivated" error
11. As TTO Officer: open disclosure → TTO action panel visible; update status to `under_review`
12. Create a patent linked to the disclosure → patent appears in portfolio
13. Add a deadline to the patent → appears in dashboard "upcoming deadlines" for TTO staff
14. Record a revenue entry with 3 distributions summing to 100% → distributions calculated correctly
15. Visit `/portal/reports/disclosures` → stats visible; click CSV export → file downloads
16. Visit `/portal/admin/users` as a student role → 403 response
17. Visit `/portal/patents` as a student role → 403 response
