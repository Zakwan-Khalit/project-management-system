<!-- Edit Event Form -->
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <nav aria-label="breadcrumb" style="margin-bottom: 1.5rem;">
                    <ol style="display: flex; list-style: none; padding: 1rem 1.25rem; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.75rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2); border: none;">
                        <li style="display: flex; align-items: center;">
                            <a href="<?= base_url('events') ?>" style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.25rem 0.5rem; border-radius: 0.375rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.15)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)'">
                                <i class="fas fa-calendar-alt" style="margin-right: 0.5rem; font-size: 0.9rem;"></i>
                                Events
                            </a>
                            <span style="margin: 0 0.75rem; color: #e2e8f0; font-size: 1.1rem; font-weight: 300;">›</span>
                        </li>
                        <li style="color: #f7fafc; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; padding: 0.25rem 0.5rem; background: rgba(255,255,255,0.1); border-radius: 0.375rem; backdrop-filter: blur(10px);">
                            <i class="fas fa-edit" style="margin-right: 0.5rem; font-size: 0.85rem; opacity: 0.9;"></i>
                            Edit Event
                        </li>
                    </ol>
                </nav>
                <h1 class="page-title">
                    <i class="fas fa-edit me-3"></i>
                    Edit Event
                </h1>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-lg-8 col-xl-9">
            <div class="form-card">
                <div class="card-header">
                    <h5 class="card-title">Event Details</h5>
                </div>
                <div class="card-body">
                    <?= form_open('events/edit/' . $event['id'], ['class' => 'event-form']) ?>
                        
                        <!-- Basic Information -->
                        <div class="form-section">
                            <h6 class="section-title">Basic Information</h6>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="title" class="form-label required">Event Title</label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               value="<?= old('title', $event['title']) ?>" required>
                                        <?php if (isset($errors['title'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['title'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="event_type" class="form-label required">Event Type</label>
                                        <select class="form-select" id="event_type" name="event_type" required>
                                            <option value="">Select Type</option>
                                            <option value="meeting" <?= old('event_type', $event['event_type']) === 'meeting' ? 'selected' : '' ?>>Meeting</option>
                                            <option value="deadline" <?= old('event_type', $event['event_type']) === 'deadline' ? 'selected' : '' ?>>Deadline</option>
                                            <option value="milestone" <?= old('event_type', $event['event_type']) === 'milestone' ? 'selected' : '' ?>>Milestone</option>
                                            <option value="training" <?= old('event_type', $event['event_type']) === 'training' ? 'selected' : '' ?>>Training</option>
                                            <option value="review" <?= old('event_type', $event['event_type']) === 'review' ? 'selected' : '' ?>>Review</option>
                                            <option value="other" <?= old('event_type', $event['event_type']) === 'other' ? 'selected' : '' ?>>Other</option>
                                        </select>
                                        <?php if (isset($errors['event_type'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['event_type'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $event['description']) ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="invalid-feedback d-block"><?= $errors['description'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div class="form-section">
                            <h6 class="section-title">Date & Time</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_datetime" class="form-label required">Start Date & Time</label>
                                        <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" 
                                               value="<?= old('start_datetime', date('Y-m-d\TH:i', strtotime($event['start_datetime']))) ?>" required>
                                        <?php if (isset($errors['start_datetime'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['start_datetime'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_datetime" class="form-label required">End Date & Time</label>
                                        <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" 
                                               value="<?= old('end_datetime', date('Y-m-d\TH:i', strtotime($event['end_datetime']))) ?>" required>
                                        <?php if (isset($errors['end_datetime'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['end_datetime'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       value="<?= old('location', $event['location']) ?>" placeholder="Meeting room, address, or online link">
                                <?php if (isset($errors['location'])): ?>
                                    <div class="invalid-feedback d-block"><?= $errors['location'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Project & Attendees -->
                        <div class="form-section">
                            <h6 class="section-title">Project & Attendees</h6>
                            
                            <div class="mb-3">
                                <label for="project_id" class="form-label">Related Project</label>
                                <select class="form-select" id="project_id" name="project_id">
                                    <option value="">No specific project</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= $project['id'] ?>" <?= old('project_id', $event['project_id']) == $project['id'] ? 'selected' : '' ?>>
                                            <?= esc($project['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">If a project is selected, only project members will be available for invitation. Leave blank to invite any user.</div>
                                <?php if (isset($errors['project_id'])): ?>
                                    <div class="invalid-feedback d-block"><?= $errors['project_id'] ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="attendees" class="form-label">Attendees</label>
                                <select class="form-select" id="attendees" name="attendees[]" multiple>
                                    <!-- Options will be populated by JavaScript based on project selection -->
                                </select>
                                <div class="form-text">All system users are available when no project is selected. Hold Ctrl/Cmd to select multiple attendees.</div>
                                <?php if (isset($errors['attendees'])): ?>
                                    <div class="invalid-feedback d-block"><?= $errors['attendees'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                Update Event
                            </button>
                            <a href="<?= base_url('events') ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                        </div>

                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <!-- Event Preview -->
        <div class="col-lg-4 col-xl-3">
            <div class="preview-card">
                <div class="card-header">
                    <h6 class="card-title">Event Preview</h6>
                </div>
                <div class="card-body">
                    <div class="event-preview">
                        <div class="preview-title" id="previewTitle"><?= esc($event['title']) ?></div>
                        <div class="preview-type" id="previewType"><?= ucfirst($event['event_type']) ?></div>
                        <div class="preview-datetime" id="previewDatetime">
                            <i class="fas fa-clock me-2"></i>
                            <?= date('M d, Y g:i A', strtotime($event['start_datetime'])) ?>
                        </div>
                        <div class="preview-location" id="previewLocation" style="<?= $event['location'] ? '' : 'display: none;' ?>">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span><?= esc($event['location']) ?></span>
                        </div>
                        <div class="preview-project" id="previewProject" style="<?= $event['project_name'] ? '' : 'display: none;' ?>">
                            <i class="fas fa-folder me-2"></i>
                            <span><?= esc($event['project_name'] ?? '') ?></span>
                        </div>
                        <div class="preview-description" id="previewDescription" style="<?= $event['description'] ? '' : 'display: none;' ?>">
                            <span><?= esc($event['description']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Events Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for attendees
    $('#attendees').select2({
        placeholder: 'Select attendees',
        allowClear: true,
        width: '100%'
    });

    // Load users based on current project
    const currentProjectId = '<?= $event['project_id'] ?? '' ?>';
    loadUsers(currentProjectId);

    // Handle project selection change
    document.getElementById('project_id').addEventListener('change', function() {
        const projectId = this.value;
        loadUsers(projectId);
        updatePreview();
    });

    // Real-time preview updates
    document.getElementById('title').addEventListener('input', updatePreview);
    document.getElementById('event_type').addEventListener('change', updatePreview);
    document.getElementById('start_datetime').addEventListener('change', updatePreview);
    document.getElementById('end_datetime').addEventListener('change', updatePreview);
    document.getElementById('location').addEventListener('input', updatePreview);
    document.getElementById('description').addEventListener('input', updatePreview);

    function loadUsers(projectId = null) {
        const attendeesSelect = document.getElementById('attendees');
        const url = projectId ? 
            `<?= base_url('events/getProjectUsers/') ?>${projectId}` : 
            '<?= base_url('events/getAllUsers') ?>';
        
        // Store current selections
        const currentSelection = Array.from(attendeesSelect.selectedOptions).map(option => option.value);
        
        // Clear current options
        $('#attendees').empty();
        
        fetch(url)
            .then(response => response.json())
            .then(users => {
                users.forEach(user => {
                    // Create display name with position
                    const displayName = user.role_name ? 
                        `${user.name} (${user.role_name})` : 
                        user.name;
                    const option = new Option(displayName, user.id);
                    attendeesSelect.add(option);
                });
                
                // Restore current attendees
                <?php if (isset($attendees) && !empty($attendees)): ?>
                const currentAttendees = <?= json_encode(array_column($attendees, 'user_id')) ?>;
                currentAttendees.forEach(userId => {
                    $('#attendees option[value="' + userId + '"]').prop('selected', true);
                });
                <?php endif; ?>
                
                $('#attendees').trigger('change');
            })
            .catch(error => {
                console.error('Error loading users:', error);
            });
    }

    function updatePreview() {
        const title = document.getElementById('title').value || 'Event Title';
        const type = document.getElementById('event_type').value || 'Event Type';
        const startDate = document.getElementById('start_datetime').value;
        const endDate = document.getElementById('end_datetime').value;
        const location = document.getElementById('location').value;
        const description = document.getElementById('description').value;
        const projectSelect = document.getElementById('project_id');
        const projectName = projectSelect.options[projectSelect.selectedIndex]?.text;

        // Update preview elements
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('previewType').textContent = type.charAt(0).toUpperCase() + type.slice(1);

        // Update datetime
        if (startDate) {
            const start = new Date(startDate);
            const end = endDate ? new Date(endDate) : null;
            let datetimeText = start.toLocaleDateString() + ' ' + start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            if (end && end.getTime() !== start.getTime()) {
                datetimeText += ' - ' + end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            }
            document.getElementById('previewDatetime').innerHTML = '<i class="fas fa-clock me-2"></i>' + datetimeText;
        }

        // Update location
        const locationEl = document.getElementById('previewLocation');
        if (location) {
            locationEl.querySelector('span').textContent = location;
            locationEl.style.display = 'block';
        } else {
            locationEl.style.display = 'none';
        }

        // Update project
        const projectEl = document.getElementById('previewProject');
        if (projectName && projectName !== 'No specific project') {
            projectEl.querySelector('span').textContent = projectName;
            projectEl.style.display = 'block';
        } else {
            projectEl.style.display = 'none';
        }

        // Update description
        const descriptionEl = document.getElementById('previewDescription');
        if (description) {
            descriptionEl.querySelector('span').textContent = description;
            descriptionEl.style.display = 'block';
        } else {
            descriptionEl.style.display = 'none';
        }
    }

    updatePreview();
});
</script>

<!-- Include events CSS -->
<link rel="stylesheet" href="<?= base_url('assets/css/events.css') ?>">

<!-- Additional form styles -->
<style>
.form-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #f1f3f4;
}

.form-card .card-header {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.5rem;
    border-radius: 1rem 1rem 0 0;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f7fafc;
}

.form-section:last-of-type {
    border-bottom: none;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.required::after {
    content: ' *';
    color: #e53e3e;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-start;
    margin-top: 2rem;
}

.preview-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #f1f3f4;
}

.event-preview {
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.5rem;
    border: 1px solid #e2e8f0;
}

.preview-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.preview-type {
    font-size: 0.85rem;
    color: #4a5568;
    text-transform: uppercase;
    font-weight: 500;
    margin-bottom: 1rem;
}

.preview-datetime,
.preview-location,
.preview-project {
    font-size: 0.9rem;
    color: #4a5568;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.preview-description {
    font-size: 0.9rem;
    color: #718096;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    line-height: 1.5;
}
</style>
