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

    

    <!-- Header -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; margin-bottom: 2rem; overflow: hidden;">
        <div style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                <div>
                    <h1 style="margin: 0; font-size: 2rem; font-weight: 700; color: #1f2937; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-sitemap" style="color: #667eea; font-size: 1.8rem;"></i>
                        Project Scope
                    </h1>
                    <p style="color: #6b7280; line-height: 1.6; margin: 0.5rem 0 0 0; font-size: 1rem;">
                        Manage project scopes and their associated activities.
                    </p>
                </div>
                <div>
                    <button class="btn btn-primary" id="addScopeBtn" style="border-radius: 0.5rem; padding: 0.75rem 1.5rem; font-weight: 600; box-shadow: 0 4px 12px rgba(102,126,234,0.3);">
                        <i class="fas fa-plus me-2"></i>Add Project Scope
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #f1f3f4; overflow: hidden;">
        <div style="padding: 2rem;">
            <div class="scope-list" id="scopeList">
                <!-- Dynamic scope items will be loaded here -->
            </div>
        </div>
    </div><script>
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
                            <button class="btn btn-primary btn-lg" id="createScopeBtn" style="padding: 0.75rem 2rem; font-size: 1.1rem; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(102,126,234,0.3);">
                                <i class="fas fa-plus me-2"></i>Create Scope
                            </button>
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
            <div class="scope-card mb-4" data-scope-id="${scope.id}" style="background: white; border-radius: 1rem; border: 1px solid #e2e8f0; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 8px 15px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)'">
                <div class="scope-header d-flex justify-content-between align-items-center mb-4">
                    <div class="scope-info">
                        <h3 style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #1f2937; margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-layer-group" style="color: #667eea; font-size: 1.2rem;"></i>
                            ${scope.name}
                        </h3>
                    </div>
                    <div class="scope-actions d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary add-components-btn" data-scope-id="${scope.id}" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500;">
                            <i class="fas fa-plus me-1"></i>Add Components
                        </button>
                        <button class="btn btn-sm btn-outline-secondary edit-scope-btn" data-scope-id="${scope.id}" style="border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500;">
                            <i class="fas fa-cog me-1"></i>Edit
                        </button>
                    </div>
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
                        if (taskRes.success && Array.isArray(taskRes.tasks) && taskRes.tasks.length > 0) {
                            let sum = 0;
                            let count = 0;
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
                                    <div class="component-name" style="font-weight: 600; color: #374151;">${template.name}</div>
                                </div>
                                <div class="component-actions d-flex align-items-center gap-2">
                                    <div class="progress-indicator d-flex flex-column align-items-center">
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
                                    <div class="component-name" style="font-weight: 600; color: #374151;">${template.name}</div>
                                </div>
                                <div class="component-actions d-flex align-items-center gap-2">
                                    <div class="progress-indicator d-flex flex-column align-items-center">
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
        Swal.fire({
            title: 'Add Project Scope',
            html: `
                <div style="text-align: left;">
                    <label for="scopeName" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Scope Name</label>
                    <input type="text" id="scopeName" class="swal2-input" placeholder="Enter scope name">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Create Scope',
            preConfirm: () => {
                const name = document.getElementById('scopeName').value;
                if (!name) {
                    Swal.showValidationMessage('Scope name is required');
                    return false;
                }
                return { name };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('activity/create_scope') ?>',
                    method: 'POST',
                    data: {
                        project_id: projectId,
                        name: result.value.name
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Creating Scope...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Created!', 'Scope created successfully.', 'success').then(() => {
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
    });
    
    // Edit Scope - Enhanced with component management and delete scope
    $('#scopeList').on('click', '.edit-scope-btn', function() {
        const scopeId = $(this).data('scope-id');
        const scopeCard = $(this).closest('.scope-card');
        const currentName = scopeCard.find('.scope-info h4').text();
        
        // Get current scope components
        const currentComponents = [];
        scopeCard.find('.component-item').each(function() {
            const templateId = $(this).data('template-id');
            const templateName = $(this).find('.component-name').text();
            currentComponents.push({ id: templateId, name: templateName });
        });
        
        // Build components list HTML
        const componentsListHtml = currentComponents.length > 0 
            ? currentComponents.map(comp => 
                `<div class="d-flex justify-content-between align-items-center p-2 border rounded mb-2" data-template-id="${comp.id}">
                    <div class="component-edit-container flex-grow-1">
                        <input type="text" class="form-control form-control-sm component-name-input" value="${comp.name}" data-template-id="${comp.id}" data-original-name="${comp.name}" style="border: none; background: transparent; font-weight: 500;">
                    </div>
                    <div class="d-flex gap-1">
                        <button type="button" class="btn btn-sm btn-outline-success save-component-name" data-template-id="${comp.id}" title="Save changes" style="display: none;">
                            <i class="fas fa-check"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-component-from-edit" data-template-id="${comp.id}" title="Remove component">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>`
            ).join('')
            : '<div class="text-muted text-center p-3">No components in this scope</div>';
        
        Swal.fire({
            title: 'Edit Scope',
            html: `
                <div style="text-align: left;">
                    <label for="editScopeName" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Scope Name</label>
                    <input type="text" id="editScopeName" class="swal2-input" value="${currentName}" style="margin-bottom: 1rem;">
                    
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Components in this Scope</label>
                    <div id="editComponentsList" style="max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem; margin-bottom: 1rem;">
                        ${componentsListHtml}
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" id="deleteScopeFromEdit" class="btn btn-sm btn-outline-danger flex-fill">
                            <i class="fas fa-trash"></i> Delete Scope
                        </button>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update Scope',
            width: '600px',
            didOpen: () => {
                // Track original component names for change detection
                const originalNames = {};
                $('#editComponentsList .component-name-input').each(function() {
                    const templateId = $(this).data('template-id');
                    const originalName = $(this).data('original-name');
                    originalNames[templateId] = originalName;
                });
                
                // Handle component name input changes
                $('#editComponentsList').on('input', '.component-name-input', function() {
                    const templateId = $(this).data('template-id');
                    const currentValue = $(this).val().trim();
                    const originalValue = originalNames[templateId];
                    const saveBtn = $(this).closest('.d-flex').find('.save-component-name');
                    
                    // Show/hide save button based on changes
                    if (currentValue !== originalValue && currentValue !== '') {
                        saveBtn.show();
                    } else {
                        saveBtn.hide();
                    }
                });
                
                // Handle component name changes on Enter key
                $('#editComponentsList').on('keypress', '.component-name-input', function(e) {
                    if (e.which === 13) { // Enter key
                        const saveBtn = $(this).closest('.d-flex').find('.save-component-name');
                        if (saveBtn.is(':visible')) {
                            saveBtn.click();
                        }
                    }
                });

                // Handle save component name
                $('#editComponentsList').on('click', '.save-component-name', function() {
                    const templateId = $(this).data('template-id');
                    const nameInput = $(this).closest('.d-flex').find('.component-name-input');
                    const newName = nameInput.val().trim();
                    const saveBtn = $(this);
                    
                    if (!newName) {
                        Swal.showValidationMessage('Component name cannot be empty');
                        return;
                    }

                    // Show loading state
                    const originalHtml = saveBtn.html();
                    saveBtn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
                    
                    $.ajax({
                        url: '<?= base_url('activity/update_component_name') ?>',
                        method: 'POST',
                        data: {
                            template_id: templateId,
                            name: newName,
                            project_id: projectId
                        },
                        dataType: 'json',
                        success: function(res) {
                            if (res.success) {
                                // Update the original name and hide save button
                                nameInput.data('original-name', newName);
                                originalNames[templateId] = newName;
                                saveBtn.hide().html(originalHtml).prop('disabled', false);
                                
                                // Show success feedback
                                nameInput.css('background-color', '#d4edda');
                                setTimeout(() => {
                                    nameInput.css('background-color', 'transparent');
                                }, 1000);
                                
                                // Show success toast
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Component name updated successfully'
                                });
                            } else {
                                saveBtn.html(originalHtml).prop('disabled', false);
                                Swal.fire('Error', res.message || 'Failed to update component name.', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating component name:', error);
                            saveBtn.html(originalHtml).prop('disabled', false);
                            Swal.fire('Error', 'Failed to update component name.', 'error');
                        }
                    });
                });

                // Handle component removal from edit modal
                $('#editComponentsList').on('click', '.remove-component-from-edit', function() {
                    const templateId = $(this).data('template-id');
                    const componentDiv = $(this).closest('div');
                    
                    Swal.fire({
                        title: 'Delete Component?',
                        text: 'This will permanently delete the component.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel'
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
                                        // Just reload the page directly
                                        loadScopes();
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
                        cancelButtonText: 'Cancel'
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
                return { name };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('activity/update_scope') ?>',
                    method: 'POST',
                    data: {
                        scope_id: scopeId,
                        name: result.value.name,
                        project_id: projectId
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Updating Scope...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Updated!', 'Scope updated successfully.', 'success').then(() => {
                                loadScopes();
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Failed to update scope.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating scope:', error);
                        Swal.fire('Error', 'Failed to update scope.', 'error');
                    }
                });
            }
        });
    });
    
    // Delete Scope
    $('#scopeList').on('click', '.delete-scope-btn', function() {
        const scopeId = $(this).data('scope-id');
        const scopeName = $(this).closest('.scope-card').find('.scope-info h4').text();
        
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
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Deleting Scope...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Deleted!', 'Scope deleted successfully.', 'success');
                            loadScopes();
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
        
        // Predefined template options
        const predefinedTemplates = [
            'Business Requirement Specification',
            'Final Acceptance Testing',
            'User Acceptance Testing',
            'System Design Document',
            'Technical Specification',
            'Database Design',
            'API Documentation',
            'Frontend Development',
            'Backend Development',
            'Testing & QA',
            'Code Review',
            'Deployment',
            'User Training',
            'Documentation',
            'Security Testing',
            'Performance Testing',
            'Bug Fixes',
            'Feature Enhancement',
            'System Integration',
            'Data Migration'
        ];
        
        const predefinedOptions = predefinedTemplates.map(name => 
            `<option value="${name}">${name}</option>`
        ).join('');
        
        Swal.fire({
            title: 'Add Components',
            html: `
                <div style="text-align: left;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Select Components</label>
                    <select id="componentSelect" class="form-select" multiple style="width: 100%;">
                        ${predefinedOptions}
                    </select>
                    <small class="text-muted mt-2 d-block">You can select multiple components.</small>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Add Components',
            width: '500px',
            didOpen: () => {
                // Initialize Select2 with tags for custom entries
                $('#componentSelect').select2({
                    width: '100%',
                    placeholder: 'Select or type custom component names',
                    allowClear: true,
                    tags: true,
                    tokenSeparators: [',', '\n'],
                    dropdownParent: $('.swal2-popup'), // Keep dropdown inside the modal
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
            },
            preConfirm: () => {
                const selectedComponents = $('#componentSelect').val();
                if (!selectedComponents || selectedComponents.length === 0) {
                    Swal.showValidationMessage('Please select at least one component');
                    return false;
                }
                return selectedComponents;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value.length > 0) {
                // Process all selected components
                const componentsToAdd = result.value.map(name => ({
                    name: name.trim(),
                    type: predefinedTemplates.includes(name.trim()) ? 'predefined' : 'custom'
                }));
                
                $.ajax({
                    url: '<?= base_url('activity/add_custom_template_to_scope') ?>',
                    method: 'POST',
                    data: {
                        scope_id: scopeId,
                        project_id: projectId,
                        components: JSON.stringify(componentsToAdd)
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Added!', 'Components added successfully.', 'success').then(() => {
                                loadScopes();
                            });
                        } else {
                            Swal.fire('Error', res.message || 'Failed to add components.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding components:', error);
                        Swal.fire('Error', 'Failed to add components.', 'error');
                    }
                });
            }
        });
    });
    
    // Click on component to navigate to task page
    $('#scopeList').on('click', '.component-item', function(e) {
        if ($(e.target).closest('.drag-handle').length) return;
        
        const templateId = $(this).data('template-id');
        window.location.href = '<?= base_url('activity/activity_dynamic/') ?>' + templateId + '?project_id=' + projectId;
    });
});
</script>
