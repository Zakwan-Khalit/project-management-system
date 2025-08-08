<!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" style="margin-bottom: 1rem;">
        <ol style="display: inline-flex; list-style: none; padding: 0.4rem 0.6rem; margin: 0; background: #4a5568; border-radius: 0.3rem; box-shadow: 0 2px 8px rgba(74, 85, 104, 0.15); border: none; width: fit-content;">
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('dashboard') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-home" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                </a>
                <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
            </li>
            <li style="display: flex; align-items: center;">
                <a href="<?= base_url('activity') ?>" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.75rem; transition: all 0.3s ease; display: flex; align-items: center; padding: 0.1rem 0.25rem; border-radius: 0.2rem;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0'">
                    <i class="fas fa-tasks" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                    Activities
                </a>
                <span style="margin: 0 0.3rem; color: #a0aec0; font-size: 0.8rem; font-weight: 300;">›</span>
            </li>
            <li style="color: #ffffff; font-weight: 500; font-size: 0.75rem; display: flex; align-items: center; padding: 0.1rem 0.25rem; background: rgba(255,255,255,0.1); border-radius: 0.2rem;">
                <i class="fas fa-sitemap" style="margin-right: 0.3rem; font-size: 0.7rem;"></i>
                Project Scope
            </li>
        </ol>
    </nav>


    <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1,2])): ?>
        <!-- Header Button (matching projects/index.php style) -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 1.25rem;">
            <button id="addScopeBtn"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; padding: 0.75rem 1.25rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);"
                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 18px rgba(102, 126, 234, 0.4)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)';">
                <i class="fas fa-plus" style="margin-right: 0.5rem;"></i>
                Add Project Scope
            </button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        <div style="padding: 2rem;">
            <div class="scope-list" id="scopeList">
                <!-- Dynamic scope items will be loaded here -->
            </div>
        </div>
    </div>
    
<script>
// Load scopes dynamically via AJAX
$(document).ready(function() {
    var projectId = <?= isset($project['id']) ? json_encode($project['id']) : 'null' ?>;
    
    // Load scopes
    loadScopes();
    
    function loadScopes() {
        $.ajax({
            url: '<?= base_url('activity/get_project_scopes') ?>',
            method: 'GET',
            data: {
                project_id: projectId
            },
            dataType: 'json',
            success: function(res) {
                $('#scopeList').empty();
                if (res.success && Array.isArray(res.scopes) && res.scopes.length > 0) {
                    res.scopes.forEach(function(scope) {
                        renderScope(scope);
                    });
                } else {
                    // No scopes: show message and Add Scope button
                    $('#scopeList').html(`
                        <div class="text-center py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 1.5rem; border: 2px dashed #cbd5e1;">
                            <div class="mb-4">
                                <i class="fas fa-sitemap" style="font-size: 4rem; color: #94a3b8; margin-bottom: 1rem;"></i>
                            </div>
                            <h4 style="color: #475569; font-family: 'Poppins', sans-serif; font-weight: 600; margin-bottom: 1rem;">
                                No Project Scopes Yet
                            </h4>
                            <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
                                Create project scopes to organize your activities and manage different aspects of your project.
                            </p>
                            <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1,2])): ?>
                            <button id="createScopeBtn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 0.5rem; padding: 0.75rem 1.25rem; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 18px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)';">
                                <i class="fas fa-plus me-2"></i>Create Scope
                            </button>
                            <?php endif; ?>
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading scopes:', error);
                $('#scopeList').html('<div class="text-center text-danger" style="padding:2rem;">Error loading scopes. Please refresh the page.</div>');
            }
        });
    }
    
    function renderScope(scope) {
        const scopeCard = `
            <div class="scope-card mb-4" data-scope-id="${scope.id}" data-scope-name="${scope.name}" ${scope.scope_lookup_id ? `data-scope-lookup-id='${scope.scope_lookup_id}'` : ''} style="background: white; border-radius: 1rem; border: 1px solid #e2e8f0; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 8px 15px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)'">
                <div class="scope-header d-flex justify-content-between align-items-center mb-4">
                    <div class="scope-info">
                        <h3 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #1f2937; margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-layer-group" style="color: #667eea; font-size: 1.2rem;"></i>
                            ${scope.name}
                        </h3>
                    </div>
                    <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1,2,3])): ?>
                    <div class="scope-actions d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary add-components-btn" data-scope-id="${scope.id}" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500;">
                            <i class="fas fa-plus me-1"></i>Add Components
                        </button>
                        <button class="btn btn-sm btn-outline-secondary edit-scope-btn" data-scope-id="${scope.id}" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500;">
                            <i class="fas fa-cog me-1"></i>Edit
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="components-container" id="components-${scope.id}" style="border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                    <!-- Components will be loaded here -->
                </div>
            </div>
        `;
        $('#scopeList').append(scopeCard);
        
        // Load components for this scope
        loadScopeComponents(scope.id, scope.templates);
    }
    
    function loadScopeComponents(scopeId, templates) {
        const container = $(`#components-${scopeId}`);
        container.empty();
        
        if (templates && templates.length > 0) {
            templates.forEach(function(template) {
                // Get template progress
                $.ajax({
                    url: '<?= base_url('activity/get_tasks_by_template/') ?>' + template.id + '/' + projectId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(taskRes) {
                        let percent = 0;
                        let taskCount = 0;
                        if (taskRes.success && Array.isArray(taskRes.tasks) && taskRes.tasks.length > 0) {
                            let sum = 0;
                            let count = 0;
                            taskCount = taskRes.tasks.length;
                            taskRes.tasks.forEach(t => {
                                Object.values(t).forEach(val => {
                                    if (typeof val === 'string' && val.trim().endsWith('%')) {
                                        let num = parseFloat(val.replace(/[^\d.]/g, ''));
                                        if (!isNaN(num)) {
                                            sum += num;
                                            count++;
                                        }
                                    }
                                });
                            });
                            if (count > 0) {
                                percent = Math.round(sum / count);
                            }
                        }
                        const componentHtml = `
                            <div class="component-item" data-template-id="${template.id}" data-component-order="${template.component_order}" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#e0f2fe'; this.style.borderColor='#0ea5e9'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(14, 165, 233, 0.15)'" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <div class="component-content d-flex align-items-center gap-3">
                                    <div class="drag-handle" style="cursor: grab; color: #9ca3af;">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    <div class="component-details d-flex flex-column">
                                        <div class="component-name" style="font-weight: 600; color: #374151;">${template.name}</div>
                                        <div class="component-weightage" style="font-size: 0.875rem; color: #6b7280;">
                                            <span class="badge bg-info">${parseFloat(template.weightage || 0).toFixed(2)}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="component-actions d-flex align-items-center gap-2">
                                    <?php $userData = session('userdata'); $roleId = $userData['role_id'] ?? null; if (in_array($roleId, [1,2])): ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary edit-weightage-btn" 
                                        data-template-id="${template.id}" 
                                        data-weightage="${template.weightage || 0}"
                                        data-component-name="${template.name}"
                                        title="Edit Weightage" 
                                        style="opacity: 0.7; transition: opacity 0.3s;"
                                        onmouseover="this.style.opacity='1'"
                                        onmouseout="this.style.opacity='0.7'">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php endif; ?>
                                    <div class="progress-indicator d-flex flex-row align-items-center" style="gap: 0.5rem;">
                                        <div style="min-width: 32px; text-align: center; font-size: 0.8rem; color: #64748b; font-weight: 600; display: flex; flex-direction: column; align-items: center;">
                                            <span>${taskCount}</span>
                                            <span style="font-size: 1em; color: #636b75ff; font-weight: 400;"><strong>logs</strong></span>
                                        </div>
                                        <div style="width:36px; height:36px; position:relative;">
                                            <svg width="36" height="36" viewBox="0 0 36 36">
                                                <circle cx="18" cy="18" r="14" stroke="#e2e8f0" stroke-width="4" fill="none" />
                                                <circle cx="18" cy="18" r="14" stroke="${percent === 100 ? '#10b981' : '#06b6d4'}" stroke-width="4" fill="none" stroke-dasharray="${Math.PI * 2 * 14}" stroke-dashoffset="${Math.PI * 2 * 14 * (1 - percent / 100)}" stroke-linecap="round" transform="rotate(-90 18 18)" />
                                            </svg>
                                            <span style="position:absolute; left:0; right:0; top:0; bottom:0; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; color:#4a5568;">${percent}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.append(componentHtml);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading template progress:', error);
                        // Still show component even if progress fails
                        const componentHtml = `
                            <div class="component-item" data-template-id="${template.id}" data-component-order="${template.component_order}" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='#e0f2fe'; this.style.borderColor='#0ea5e9'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(14, 165, 233, 0.15)'" onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <div class="component-content d-flex align-items-center gap-3">
                                    <div class="drag-handle" style="cursor: grab; color: #9ca3af;">
                                        <i class="fas fa-grip-vertical"></i>
                                    </div>
                                    <div class="component-details d-flex flex-column">
                                        <div class="component-name" style="font-weight: 600; color: #374151;">${template.name}</div>
                                        <div class="component-weightage" style="font-size: 0.875rem; color: #6b7280;">
                                            <span class="badge bg-info">${parseFloat(template.weightage || 0).toFixed(2)}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="component-actions d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary edit-weightage-btn" 
                                        data-template-id="${template.id}" 
                                        data-weightage="${template.weightage || 0}"
                                        data-component-name="${template.name}"
                                        title="Edit Weightage" 
                                        style="opacity: 0.7; transition: opacity 0.3s;"
                                        onmouseover="this.style.opacity='1'"
                                        onmouseout="this.style.opacity='0.7'">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div class="progress-indicator d-flex flex-row align-items-center" style="gap: 0.5rem;">
                                        <div style="min-width: 32px; text-align: right; font-size: 0.8rem; color: #64748b; font-weight: 600;">
                                            <i class="fas fa-tasks me-1"></i>0
                                        </div>
                                        <div style="width:36px; height:36px; position:relative; display:flex; align-items:center; justify-content:center;">
                                            <span style="font-size:0.75rem; font-weight:700; color:#6b7280;">N/A</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.append(componentHtml);
                    }
                });
            });
        } else {
            container.html('<div class="text-center text-muted py-3">No components in this scope</div>');
        }
        
        // Make components sortable
        new Sortable(container[0], {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'bg-light',
            onEnd: function(evt) {
                updateComponentOrder(scopeId);
            }
        });
    }
    
    function updateComponentOrder(scopeId) {
        const container = $(`#components-${scopeId}`);
        const order = [];
        container.find('.component-item').each(function(index) {
            order.push({
                template_id: $(this).data('template-id'),
                order: index + 1
            });
        });
        
        $.ajax({
            url: '<?= base_url('activity/update_template_order') ?>',
            method: 'POST',
            data: {
                scope_id: scopeId,
                template_order: JSON.stringify(order),
                project_id: projectId
            },
            dataType: 'json',
            success: function(res) {
                if (!res.success) {
                    console.error('Failed to update component order:', res.message);
                    Swal.fire('Error', 'Failed to update component order.', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating component order:', error);
                Swal.fire('Error', 'Failed to update component order.', 'error');
            }
        });
    }
    

    // Add Scope button (both main button and fallback button)
    $(document).on('click', '#addScopeBtn, #createScopeBtn', function() {
        // Fetch available scopes from backend
        $.ajax({
            url: '<?= base_url('activity/get_available_scopes') ?>',
            method: 'GET',
            data: { project_id: projectId },
            dataType: 'json',
            success: function(res) {
                let options = '';
                if (res.success && res.scopes && res.scopes.length > 0) {
                    options = res.scopes.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
                }
                Swal.fire({
                    title: 'Add Project Scope',
                    html: `
                        <div style="text-align: left; min-height: 150px; height: 150px; display: flex; flex-direction: column; justify-content: center;">
                            <label for="scopeSelect" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Scope Name</label>
                            <select id="scopeSelect" class="form-select" style="width:100%; min-height: 48px;">
                                <option value="">Select or type scope name</option>
                                ${options}
                            </select>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Create Scope',
                    cancelButtonText: 'Cancel',
                    width: '500px',
                    customClass: {
                        cancelButton: '',
                        popup: 'swal2-modal-tall'
                    },
                    didOpen: () => {
                        $('#scopeSelect').select2({
                            width: '100%',
                            placeholder: 'Select or type scope name',
                            allowClear: true,
                            tags: true,
                            dropdownParent: $('.swal2-popup'),
                            dropdownCssClass: 'select2-dropdown-custom',
                            createTag: function (params) {
                                const term = $.trim(params.term);
                                if (term === '') {
                                    return null;
                                }
                                return {
                                    id: term,
                                    text: term,
                                    newTag: true
                                };
                            }
                        });
                        if (!$('style#select2-dropdown-custom-style').length) {
                            $('head').append('<style id="select2-dropdown-custom-style">.select2-dropdown-custom { max-height: 250px !important; overflow-y: auto !important; } </style>');
                        }
                    },
                    preConfirm: () => {
                        const scopeVal = $('#scopeSelect').val();
                        const scopeText = $('#scopeSelect option:selected').text();
                        if (!scopeVal) {
                            Swal.showValidationMessage('Please select or type a scope name');
                            return false;
                        }
                        // If the value is not a number, treat as custom
                        const isCustom = isNaN(scopeVal);
                        return { scopeVal, scopeText, isCustom };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let postData = {
                            project_id: projectId
                        };
                        if (result.value.isCustom) {
                            postData.custom_scope_name = result.value.scopeVal;
                        } else {
                            postData.scope_lookup_id = result.value.scopeVal;
                        }
                        $.ajax({
                            url: '<?= base_url('activity/create_scope') ?>',
                            method: 'POST',
                            data: postData,
                            dataType: 'json',
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        title: 'Created!',
                                        text: 'Scope created successfully.',
                                        icon: 'success',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        loadScopes();
                                    });
                                } else {
                                    Swal.fire('Error', res.message || 'Failed to create scope.', 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error creating scope:', error);
                                Swal.fire('Error', 'Failed to create scope.', 'error');
                            }
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading available scopes:', error);
                Swal.fire('Error', 'Failed to load available scopes.', 'error');
            }
        });
    });
    
    // Edit Scope - Enhanced with component management and delete scope
    $('#scopeList').on('click', '.edit-scope-btn', function() {
        const scopeId = $(this).data('scope-id');
        const scopeCard = $(this).closest('.scope-card');
        const currentName = scopeCard.data('scope-name');
        // Check if scope is from scope_lookup (assume scope_lookup_id is set as data attribute if available)
        const scopeLookupId = scopeCard.data('scope-lookup-id');

        // Get current scope components
        const currentComponents = [];
        scopeCard.find('.component-item').each(function() {
            const templateId = $(this).data('template-id');
            const templateName = $(this).find('.component-name').text();
            const weightage = $(this).find('.edit-weightage-btn').data('weightage') || 0;
            currentComponents.push({ id: templateId, name: templateName, weightage: weightage });
        });

        // Build components list HTML
        const componentsListHtml = currentComponents.length > 0 
            ? currentComponents.map(comp => 
                `<div class="component-edit-row" data-template-id="${comp.id}" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 1rem; margin-bottom: 0.75rem; transition: all 0.3s ease;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-grow-1">
                            <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280; margin-bottom: 0.25rem; display: block;">Component Name</label>
                            <input type="text" class="form-control component-name-input" 
                                value="${comp.name}" 
                                data-template-id="${comp.id}" 
                                data-original-name="${comp.name}" 
                                style="border: 1px solid #d1d5db; border-radius: 0.375rem; font-weight: 500;">
                        </div>
                        <div style="min-width: 120px;">
                            <label style="font-size: 0.75rem; font-weight: 600; color: #6b7280; margin-bottom: 0.25rem; display: block;">Weightage (%)</label>
                            <input type="number" class="form-control component-weightage-input" 
                                value="${comp.weightage}" 
                                data-template-id="${comp.id}" 
                                data-original-weightage="${comp.weightage}"
                                step="0.01" min="0" max="100" 
                                placeholder="0.00"
                                style="border: 1px solid #d1d5db; border-radius: 0.375rem; text-align: center;">
                        </div>
                        <div class="d-flex flex-column gap-1" style="min-width: 40px;">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-component-from-edit" 
                                data-template-id="${comp.id}" 
                                title="Remove component"
                                style="opacity: 0.7;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>`
            ).join('')
            : '<div class="text-muted text-center p-3">No components in this scope</div>';

        Swal.fire({
            title: 'Edit Scope',
            html: `
                <div style="text-align: left; padding: 1rem;">
                    <div class="mb-4">
                        <label for="editScopeName" style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: #374151; font-size: 1rem;">Scope Name</label>
                        <input type="text" id="editScopeName" class="swal2-input" value="${currentName}" 
                            style="margin: 0; border: 2px solid #e5e7eb; border-radius: 0.5rem; padding: 0.75rem; font-size: 1rem; width: 100%;" ${scopeLookupId ? 'disabled' : ''}>
                    </div>
                    
                    <div class="mb-4">
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: #374151; font-size: 1rem;">Components in this Scope</label>
                        <div id="editComponentsList" style="max-height: 300px; overflow-y: auto; border: 2px solid #e5e7eb; border-radius: 0.75rem; padding: 1rem; background: #fafafa;">
                            ${componentsListHtml}
                        </div>
                        <div class="mt-2">
                            <small style="color: #6b7280; font-size: 0.875rem;">
                                <i class="fas fa-info-circle me-1"></i>
                                Changes will be saved when you click "Update Scope"
                            </small>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="button" id="deleteScopeFromEdit" class="btn btn-outline-danger flex-fill" style="border-radius: 0.5rem; padding: 0.75rem;">
                            <i class="fas fa-trash me-2"></i>Delete Scope
                        </button>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update Scope',
            cancelButtonText: 'Cancel',
            width: '700px',
            customClass: {
                cancelButton: '',
            },
            didOpen: () => {
                // Handle component removal from edit modal
                $('#editComponentsList').on('click', '.remove-component-from-edit', function() {
                    const templateId = $(this).data('template-id');
                    const componentDiv = $(this).closest('.component-edit-row');
                    
                    Swal.fire({
                        title: 'Delete Component?',
                        text: 'This will permanently delete the component.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            cancelButton: '',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '<?= base_url('activity/soft_delete_component') ?>',
                                method: 'POST',
                                data: {
                                    template_id: templateId,
                                    project_id: projectId
                                },
                                dataType: 'json',
                                success: function(res) {
                                    if (res.success) {
                                        // Reload the page to ensure UI is updated
                                        location.reload();
                                    } else {
                                        Swal.fire('Error', res.message || 'Failed to delete component.', 'error');
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error deleting component:', error);
                                    Swal.fire('Error', 'Failed to delete component.', 'error');
                                }
                            });
                        }
                    });
                });
                
                // Handle delete scope from edit modal
                $('#deleteScopeFromEdit').on('click', function() {
                    Swal.fire({
                        title: 'Delete Scope',
                        text: `Are you sure you want to delete "${currentName}"? This will remove all components from this scope.`,
                        icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        cancelButton: '',
                    }
                    }).then((deleteResult) => {
                        if (deleteResult.isConfirmed) {
                            $.ajax({
                                url: '<?= base_url('activity/delete_scope') ?>',
                                method: 'POST',
                                data: { 
                                    scope_id: scopeId,
                                    project_id: projectId
                                },
                                dataType: 'json',
                                success: function(res) {
                                    if (res.success) {
                                        Swal.fire('Deleted!', 'Scope deleted successfully.', 'success').then(() => {
                                            loadScopes();
                                        });
                                    } else {
                                        Swal.fire('Error', res.message || 'Failed to delete scope.', 'error');
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error deleting scope:', error);
                                    Swal.fire('Error', 'Failed to delete scope.', 'error');
                                }
                            });
                        }
                    });
                });
            },
            preConfirm: () => {
                const name = document.getElementById('editScopeName').value;
                if (!name) {
                    Swal.showValidationMessage('Scope name is required');
                    return false;
                }
                
                // Collect all component weightage changes
                const componentChanges = [];
                $('#editComponentsList .component-weightage-input').each(function() {
                    const templateId = $(this).data('template-id');
                    const currentWeightage = $(this).val();
                    const originalWeightage = $(this).data('original-weightage');
                    
                    if (currentWeightage !== originalWeightage) {
                        // Validate weightage
                        if (currentWeightage && (isNaN(currentWeightage) || parseFloat(currentWeightage) < 0 || parseFloat(currentWeightage) > 100)) {
                            Swal.showValidationMessage('Please enter valid weightage values (0-100)');
                            return false;
                        }
                        componentChanges.push({
                            template_id: templateId,
                            weightage: currentWeightage || 0
                        });
                    }
                });
                
                // Collect all component name changes
                const nameChanges = [];
                $('#editComponentsList .component-name-input').each(function() {
                    const templateId = $(this).data('template-id');
                    const currentName = $(this).val().trim();
                    const originalName = $(this).data('original-name');
                    
                    if (currentName !== originalName) {
                        if (!currentName) {
                            Swal.showValidationMessage('Component names cannot be empty');
                            return false;
                        }
                        nameChanges.push({
                            template_id: templateId,
                            name: currentName
                        });
                    }
                });
                
                return { 
                    name: name,
                    componentChanges: componentChanges,
                    nameChanges: nameChanges
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Create array of all update promises
                const updatePromises = [];

                // Update scope name
                updatePromises.push(
                    $.ajax({
                        url: '<?= base_url('activity/update_scope') ?>',
                        method: 'POST',
                        data: {
                            scope_id: scopeId,
                            name: result.value.name,
                            project_id: projectId
                        },
                        dataType: 'json'
                    })
                );

                // Update component weightages
                result.value.componentChanges.forEach(function(change) {
                    updatePromises.push(
                        $.ajax({
                            url: '<?= base_url('activity/update_component_weightage') ?>',
                            method: 'POST',
                            data: {
                                template_id: change.template_id,
                                weightage: change.weightage
                            },
                            dataType: 'json'
                        })
                    );
                });

                // Update component names
                result.value.nameChanges.forEach(function(change) {
                    updatePromises.push(
                        $.ajax({
                            url: '<?= base_url('activity/update_component_name') ?>',
                            method: 'POST',
                            data: {
                                template_id: change.template_id,
                                name: change.name,
                                project_id: projectId
                            },
                            dataType: 'json'
                        })
                    );
                });

                // Execute all updates
                Promise.all(updatePromises).then(function(responses) {
                    const allSuccess = responses.every(res => res.success);

                    if (allSuccess) {
                        Swal.fire('Updated!', 'Scope and components updated successfully.', 'success').then(() => {
                            loadScopes();
                        });
                    } else {
                        Swal.fire('Error', 'Some updates failed. Please try again.', 'error');
                    }
                }).catch(function(error) {
                    console.error('Error updating scope:', error);
                    Swal.fire('Error', 'Failed to update scope. Please try again.', 'error');
                });
            }
        });
    });
    
    // Delete Scope
    $('#scopeList').on('click', '.delete-scope-btn', function() {
        const scopeId = $(this).data('scope-id');
        const scopeName = $(this).closest('.scope-card').data('scope-name');

        Swal.fire({
            title: 'Delete Scope',
            text: `Are you sure you want to delete "${scopeName}"? This will remove all components from this scope.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('activity/delete_scope') ?>',
                    method: 'POST',
                    data: { 
                        scope_id: scopeId,
                        project_id: projectId
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Scope deleted successfully.',
                                icon: 'success',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                loadScopes();
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Failed to delete scope.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting scope:', error);
                        Swal.fire('Error', 'Failed to delete scope.', 'error');
                    }
                });
            }
        });
    });
    
    // Add Components to Scope
    $('#scopeList').on('click', '.add-components-btn', function() {
        const scopeId = $(this).data('scope-id');
        // Load components for this scope from backend (component_lookup, not templates)
        $.ajax({
            url: '<?= base_url('activity/get_components_by_scope') ?>',
            method: 'GET',
            data: { scope_id: scopeId },
            dataType: 'json',
            success: function(res) {
                let componentOptions = '';
                if (res.success && res.components && res.components.length > 0) {
                    componentOptions = res.components.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
                }
                // Load existing project templates for table display (unchanged)
                loadProjectTemplatesTable(scopeId, function(templatesTableHtml) {
                    Swal.fire({
                        title: 'Add Components',
                        html: `
                            <div style="text-align: left;">
                                <div class="mb-3">
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Select Components</label>
                                    <select id="componentSelect" class="form-select" multiple style="width: 100%;">
                                        ${componentOptions}
                                    </select>
                                    <small class="text-muted mt-2 d-block">You can select multiple components or type to add new.</small>
                                </div>
                                <div class="mb-3">
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Weightage (%)</label>
                                    <input type="number" id="componentWeightage" class="form-control" step="0.01" min="0" max="100" placeholder="0.00" style="width: 100%;">
                                    <small class="text-muted mt-1 d-block">Enter weightage as a percentage (e.g. 25.50)</small>
                                </div>
                                <div class="mb-3">
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Existing Project Components</label>
                                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
                                        ${templatesTableHtml}
                                    </div>
                                </div>
                            </div>
                        `,
            showCancelButton: true,
            confirmButtonText: 'Add Components',
            cancelButtonText: 'Cancel',
            customClass: {
                cancelButton: '',
            },
                        width: '700px',
                        didOpen: () => {
                            // Initialize Select2 with tags for custom entries
                            $('#componentSelect').select2({
                                width: '100%',
                                placeholder: 'Select or type custom component names',
                                allowClear: true,
                                tags: true,
                                tokenSeparators: [',', '\n'],
                                dropdownParent: $('.swal2-popup'),
                                dropdownCssClass: 'select2-dropdown-custom',
                                createTag: function (params) {
                                    const term = $.trim(params.term);
                                    if (term === '') {
                                        return null;
                                    }
                                    return {
                                        id: term,
                                        text: term,
                                        newTag: true
                                    };
                                }
                            });
                            if (!$('style#select2-dropdown-custom-style').length) {
                                $('head').append('<style id="select2-dropdown-custom-style">.select2-dropdown-custom { max-height: 250px !important; overflow-y: auto !important; } </style>');
                            }
                        },
                        preConfirm: () => {
                            const selectedComponents = $('#componentSelect').val();
                            const componentWeightage = document.getElementById('componentWeightage').value;
                            if (!selectedComponents || selectedComponents.length === 0) {
                                Swal.showValidationMessage('Please select at least one component');
                                return false;
                            }
                            if (componentWeightage && (isNaN(componentWeightage) || parseFloat(componentWeightage) < 0)) {
                                Swal.showValidationMessage('Please enter a valid weightage value');
                                return false;
                            }
                            return {
                                components: selectedComponents,
                                weightage: componentWeightage || 0
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed && result.value.components.length > 0) {
                            // Process all selected components
                            const componentsToAdd = result.value.components.map(name => ({
                                name: name.trim(),
                                weightage: result.value.weightage
                            }));
                            addMultipleComponentsToScope(scopeId, componentsToAdd);
                        }
                    });
                });
            },
            error: function() {
                Swal.fire('Error', 'Failed to load components for this scope.', 'error');
            }
        });
    });
    
    function loadProjectTemplatesTable(scopeId, callback) {
        $.ajax({
            url: '<?= base_url('activity/get_project_templates') ?>',
            method: 'GET',
            data: { project_id: projectId, scope_id: scopeId },
            dataType: 'json',
            success: function(res) {
                let tableHtml = '';
                if (res.success && res.templates && res.templates.length > 0) {
                    tableHtml = `
                        <table class="table table-sm table-hover mb-0">
                            <thead style="background: #f8fafc;">
                                <tr>
                                    <th style="text-align: left; padding: 0.75rem; font-weight: 600;">Component</th>
                                    <th style="text-align: right; padding: 0.75rem; font-weight: 600;">Weightage</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    res.templates.forEach(function(template) {
                        tableHtml += `
                            <tr>
                                <td style="text-align: left; padding: 0.75rem;">${template.name}</td>
                                <td style="text-align: right; padding: 0.75rem;">
                                    <span class="badge bg-primary">${template.weightage}%</span>
                                </td>
                            </tr>
                        `;
                    });

                    tableHtml += `
                            </tbody>
                        </table>
                    `;
                } else {
                    tableHtml = '<div class="text-center text-muted p-3">No components found in this scope.</div>';
                }
                callback(tableHtml);
            },
            error: function() {
                callback('<div class="text-center text-danger p-3">Error loading components.</div>');
            }
        });
    }
    
    function addMultipleComponentsToScope(scopeId, componentsToAdd) {
        $.ajax({
            url: '<?= base_url('activity/add_custom_template_to_scope') ?>',
            method: 'POST',
            data: {
                scope_id: scopeId,
                project_id: projectId,
                components: JSON.stringify(componentsToAdd.map(comp => ({
                    name: comp.name,
                    type: 'custom',
                    weightage: comp.weightage
                })))
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        title: 'Added!',
                        text: 'Components added successfully.',
                        icon: 'success',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        loadScopes();
                    });
                } else {
                    Swal.fire('Error', res.message || 'Failed to add components.', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error adding components:', error);
                Swal.fire('Error', 'Failed to add components. Please try again.', 'error');
            }
        });
    }
    
    // Edit Component Weightage
    $('#scopeList').on('click', '.edit-weightage-btn', function() {
        const templateId = $(this).data('template-id');
        const currentWeightage = $(this).data('weightage') || 0;
        const componentName = $(this).data('component-name');
        
        Swal.fire({
            title: 'Edit Component Weightage',
            html: `
                <div style="text-align: left;">
                    <div class="mb-3">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Component</label>
                        <input type="text" class="form-control" value="${componentName}" readonly style="width: 100%; background-color: #f8f9fa;">
                    </div>
                    
                    <div class="mb-3">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Weightage (%)</label>
                        <input type="number" id="editWeightage" class="form-control" step="0.01" min="0" max="100" value="${currentWeightage}" style="width: 100%;">
                        <small class="text-muted mt-1 d-block">Enter weightage as a percentage (e.g., 25.50)</small>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update Weightage',
            cancelButtonText: 'Cancel',
            customClass: {
                cancelButton: '',
            },
            width: '500px',
            preConfirm: () => {
                const weightage = document.getElementById('editWeightage').value;
                
                if (weightage && (isNaN(weightage) || parseFloat(weightage) < 0)) {
                    Swal.showValidationMessage('Please enter a valid weightage value');
                    return false;
                }
                
                return weightage || 0;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                updateComponentWeightage(templateId, result.value);
            }
        });
    });
    
    function updateComponentWeightage(templateId, weightage) {
        $.ajax({
            url: '<?= base_url('activity/update_component_weightage') ?>',
            method: 'POST',
            data: {
                template_id: templateId,
                weightage: weightage
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    Swal.fire('Updated!', 'Component weightage updated successfully.', 'success').then(() => {
                        loadScopes();
                    });
                } else {
                    Swal.fire('Error', res.message || 'Failed to update weightage.', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating weightage:', error);
                Swal.fire('Error', 'Failed to update weightage. Please try again.', 'error');
            }
        });
    }
    
    // Click on component to navigate to task page
    $('#scopeList').on('click', '.component-item', function(e) {
        if ($(e.target).closest('.drag-handle').length) return;
        if ($(e.target).closest('.edit-weightage-btn').length) return;
        
        const templateId = $(this).data('template-id');
        window.location.href = '<?= base_url('activity/activity_dynamic/') ?>' + templateId + '?project_id=' + projectId;
    });
});
</script>
