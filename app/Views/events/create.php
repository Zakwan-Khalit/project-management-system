<!-- Create Event Form -->
<div class="container-fluid" style="width: 100%; padding: 0 1.5rem;">
    <!-- Page Header -->
    <div class="row mb-4" style="margin-bottom: 2rem !important;">
        <div class="col-12" style="width: 100%;">
            <div class="page-header" style="margin-bottom: 2rem;">
                <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
                    <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
                        <li style="display: flex; align-items: center;">
                            <a href="<?= base_url('events') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                                <i class="fas fa-calendar-alt" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                                Events
                            </a>
                            <span style="margin: 0 0.5rem; color: #9ca3af; font-size: 0.65rem;">›</span>
                        </li>
                        <li style="color: #f7fafc; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem;">
                            <i class="fas fa-plus" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                            Create Event
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="row" style="display: flex; flex-wrap: wrap; margin-right: -0.75rem; margin-left: -0.75rem;">
        <div class="col-lg-8 col-xl-9" style="flex: 0 0 auto; width: 75%; max-width: 75%; padding-right: 0.75rem; padding-left: 0.75rem;">
            <div class="form-card" style="background: white; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #f1f3f4;">
                <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 1rem 1rem 0 0;">
                    <h5 class="card-title">Create New Event</h5>
                </div>
                <div class="card-body" style="padding: 2rem;">
                    <?= form_open('events/create', ['class' => 'event-form']) ?>
                        
                        <!-- Basic Information -->
                        <div class="form-section" style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f7fafc;">
                            <h6 class="section-title" style="font-size: 1.1rem; font-weight: 600; color: #2d3748; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0;">Basic Information</h6>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="title" class="form-label required" style="font-weight: 600;">Event Title</label>
                                        <input type="text" class="form-control" id="title" name="title" 
                                               value="<?= old('title') ?>" required>
                                        <?php if (isset($errors['title'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['title'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="event_type" class="form-label required" style="font-weight: 600;">Event Type</label>
                                        <select class="form-select" id="event_type" name="event_type" required>
                                            <option value="">Select Type</option>
                                            <option value="meeting" <?= old('event_type') === 'meeting' ? 'selected' : '' ?>>Meeting</option>
                                            <option value="deadline" <?= old('event_type') === 'deadline' ? 'selected' : '' ?>>Deadline</option>
                                            <option value="milestone" <?= old('event_type') === 'milestone' ? 'selected' : '' ?>>Milestone</option>
                                            <option value="training" <?= old('event_type') === 'training' ? 'selected' : '' ?>>Training</option>
                                            <option value="review" <?= old('event_type') === 'review' ? 'selected' : '' ?>>Review</option>
                                            <option value="other" <?= old('event_type') === 'other' ? 'selected' : '' ?>>Other</option>
                                        </select>
                                        <?php if (isset($errors['event_type'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['event_type'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label" style="font-weight: 600;">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <div class="invalid-feedback d-block"><?= $errors['description'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div class="form-section" style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f7fafc;">
                            <h6 class="section-title" style="font-size: 1.1rem; font-weight: 600; color: #2d3748; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0;">Date & Time</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_datetime" class="form-label required" style="font-weight: 600;">Start Date & Time</label>
                                        <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" 
                                               value="<?= old('start_datetime') ?>" required>
                                        <?php if (isset($errors['start_datetime'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['start_datetime'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_datetime" class="form-label required" style="font-weight: 600;">End Date & Time</label>
                                        <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" 
                                               value="<?= old('end_datetime') ?>" required>
                                        <?php if (isset($errors['end_datetime'])): ?>
                                            <div class="invalid-feedback d-block"><?= $errors['end_datetime'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="location" class="form-label" style="font-weight: 600;">Location</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       value="<?= old('location') ?>" placeholder="Meeting room, address, or online link">
                                <?php if (isset($errors['location'])): ?>
                                    <div class="invalid-feedback d-block"><?= $errors['location'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Project & Attendees -->
                        <div class="form-section" style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f7fafc;">
                            <h6 class="section-title" style="font-size: 1.1rem; font-weight: 600; color: #2d3748; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e2e8f0;">Project & Attendees</h6>
                            
                            <div class="mb-3">
                                <label for="project_id" class="form-label" style="font-weight: 600;">Related Project</label>
                                <select class="form-select" id="project_id" name="project_id">
                                    <option value="">No specific project</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= $project['id'] ?>" <?= old('project_id') == $project['id'] ? 'selected' : '' ?>>
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
                                <label for="attendees" class="form-label" style="font-weight: 600;">Attendees</label>
                                <select class="form-select" id="attendees" name="attendees[]" multiple>
                                    <!-- Options will be populated by JavaScript based on project selection -->
                                </select>
                                <div class="form-text">All system users are available when no project is selected.</div>
                                <?php if (isset($errors['attendees'])): ?>
                                    <div class="invalid-feedback d-block"><?= $errors['attendees'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions" style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                            <button type="submit" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; padding: 0.75rem 1.25rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 18px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)';">
                                <i class="fas fa-save me-2"></i>
                                Create Event
                            </button>
                            <a href="<?= base_url('events') ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times" style="margin-right: 0.5rem;"></i>
                                Cancel
                            </a>
                        </div>

                    <?= form_close() ?>
                </div>
            </div>
        </div>

        <!-- Event Preview -->
        <div class="col-lg-4 col-xl-3" style="flex: 0 0 auto; width: 25%; max-width: 25%; padding-right: 0.75rem; padding-left: 0.75rem;">
            <div class="preview-card" style="background: white; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #f1f3f4;">
                <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 1rem 1rem 0 0;">
                    <h6 class="card-title">Event Preview</h6>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="event-preview" style="padding: 1rem; background: #f8fafc; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                        <div class="preview-title" id="previewTitle" style="font-size: 1.1rem; font-weight: 600; color: #2d3748; margin-bottom: 0.5rem;">Event Title</div>
                        <div class="preview-type" id="previewType" style="font-size: 0.85rem; color: #4a5568; text-transform: uppercase; font-weight: 500; margin-bottom: 1rem;">Event Type</div>
                        <div class="preview-datetime" id="previewDatetime" style="font-size: 0.9rem; color: #4a5568; margin-bottom: 0.5rem; display: flex; align-items: center;">
                            <i class="fas fa-clock me-2"></i>
                            Select date & time
                        </div>
                        <div class="preview-location" id="previewLocation" style="font-size: 0.9rem; color: #4a5568; margin-bottom: 0.5rem; display: flex; align-items: center; display: none;">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span></span>
                        </div>
                        <div class="preview-project" id="previewProject" style="font-size: 0.9rem; color: #4a5568; margin-bottom: 0.5rem; display: flex; align-items: center; display: none;">
                            <i class="fas fa-folder me-2"></i>
                            <span></span>
                        </div>
                        <div class="preview-description" id="previewDescription" style="font-size: 0.9rem; color: #718096; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; line-height: 1.5; display: none;">
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="tips-card mt-3" style="background: white; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid #f1f3f4; margin-top: 1rem !important;">
                <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; padding: 1.5rem; border-radius: 1rem 1rem 0 0;">
                    <h6 class="card-title">
                        <i class="fas fa-lightbulb me-2"></i>
                        Tips
                    </h6>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <ul class="tips-list" style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid #f7fafc; color: #4a5568; font-size: 0.9rem;">💡 Use clear, descriptive titles for your events</li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid #f7fafc; color: #4a5568; font-size: 0.9rem;">💡 Add a location for in-person meetings</li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid #f7fafc; color: #4a5568; font-size: 0.9rem;">💡 Link events to projects for better organization</li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid #f7fafc; color: #4a5568; font-size: 0.9rem;">💡 Invite relevant team members to collaborate</li>
                        <li style="padding: 0.5rem 0; color: #4a5568; font-size: 0.9rem;">💡 Set reminders for important deadlines</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 CSS and JS are included in main.php -->

<!-- Events Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for attendees
    $('#attendees').select2({
        placeholder: 'Select attendees',
        allowClear: true,
        width: '100%'
    });

    // Load initial users
    loadUsers();

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

    // Set default start time to current time + 1 hour
    const now = new Date();
    now.setHours(now.getHours() + 1);
    now.setMinutes(0);
    document.getElementById('start_datetime').value = now.toISOString().slice(0, 16);
    
    // Set default end time to start time + 1 hour
    const endTime = new Date(now);
    endTime.setHours(endTime.getHours() + 1);
    document.getElementById('end_datetime').value = endTime.toISOString().slice(0, 16);

    updatePreview();
});
</script>


