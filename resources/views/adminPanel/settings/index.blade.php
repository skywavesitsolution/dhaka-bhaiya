@extends('adminPanel.master')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <style>
        /* Modern React-like Aesthetics */
        .settings-wrapper {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .custom-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            background: #fff;
            overflow: hidden;
            margin-bottom: 24px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .custom-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }

        .card-header-modern {
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 24px;
            font-weight: 600;
            color: #1e293b;
            font-size: 1.05rem;
        }

        .nav-pills .nav-link {
            border-radius: 8px;
            padding: 12px 20px;
            color: #64748b;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 6px;
        }

        .nav-pills .nav-link.active {
            background-color: #6366f1;
            /* Indigo */
            color: #fff;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.25);
        }

        .nav-pills .nav-link:hover:not(.active) {
            background-color: #f1f5f9;
            color: #475569;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            transition: all 0.25s;
            background-color: #fcfcfd;
        }

        .form-control:focus {
            border-color: #6366f1;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px 0 0 8px;
            color: #94a3b8;
        }

        .form-control:focus+.input-group-text {
            border-color: #6366f1;
        }

        .upload-box {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .upload-box:hover {
            border-color: #6366f1;
            background: #eff6ff;
        }

        .upload-box input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-icon {
            font-size: 32px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .btn-modern {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border-radius: 8px;
            padding: 10px 28px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
            color: #fff;
        }

        .img-preview-box {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 8px;
            background: #fff;
            max-width: 180px;
            margin: 0 auto 15px auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .img-preview-box img {
            border-radius: 4px;
            max-width: 100%;
        }

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        /* Cropper Styles */
        .img-container {
            max-width: 100%;
            height: 450px;
        }

        .floating-success {
            display: none;
            color: #10b981;
            font-weight: 600;
            font-size: 0.85rem;
            margin-top: 12px;
            background: #d1fae5;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-block;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
    </style>

    <div class="settings-wrapper">
        <div class="row pt-4 pb-2">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h3 style="font-weight: 700; color: #1e293b; letter-spacing: -0.5px;">Platform Settings</h3>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert"
                style="border-radius: 10px; border-left: 5px solid #10b981; background-color: #ecfdf5; border-color: #d1fae5; color: #065f46;">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('settings.store') }}" method="POST" id="settingsForm" enctype="multipart/form-data">
            @csrf

            <div class="row mt-2">
                <!-- Left Navigation Tabs -->
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="custom-card p-3 sticky-top" style="top: 20px; z-index: 1;">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active text-start" id="v-pills-general-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-general" type="button" role="tab"><i class="uil-building me-2"></i>
                                General Info</button>
                            <button class="nav-link text-start" id="v-pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-contact" type="button" role="tab"><i class="uil-phone me-2"></i>
                                Contact Details</button>
                            <button class="nav-link text-start" id="v-pills-brand-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-brand" type="button" role="tab"><i class="uil-image me-2"></i>
                                Branding & Logos</button>
                        </div>
                    </div>
                </div>

                <!-- Right Content Panels -->
                <div class="col-lg-9 col-md-8">
                    <div class="tab-content" id="v-pills-tabContent">

                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel"
                            aria-labelledby="v-pills-general-tab" tabindex="0">
                            <div class="custom-card">
                                <div class="card-header-modern">General Information</div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <label class="form-label text-muted fw-bold">Company Name</label>
                                            <input type="text" name="company_name" class="form-control form-control-lg"
                                                placeholder="e.g. Skywaves IT Solution"
                                                value="{{ $settings['company_name'] ?? '' }}">
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label class="form-label text-muted fw-bold">Invoice Note / Terms</label>
                                            <textarea name="invoice_note" class="form-control" rows="4"
                                                placeholder="Thank you for your business!">{{ $settings['invoice_note'] ?? '' }}</textarea>
                                            <small class="text-muted mt-1 d-block"><i class="uil-info-circle"></i> This note
                                                will appear at the bottom of customer invoices.</small>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label text-muted fw-bold">Business Address</label>
                                            <textarea name="address" class="form-control" rows="3"
                                                placeholder="Enter primary business address">{{ $settings['address'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Details -->
                    <div class="tab-pane fade" id="v-pills-contact" role="tabpanel" aria-labelledby="v-pills-contact-tab" tabindex="0">
                        @php
                            $dynamicContacts = isset($settings['contacts']) ? json_decode($settings['contacts'], true) : [];
                            if (!is_array($dynamicContacts)) $dynamicContacts = [];
                        @endphp
                        <div class="custom-card">
                            <div class="card-header-modern d-flex justify-content-between align-items-center">
                                <span>Network & Contact Details</span>
                                <button type="button" class="btn btn-sm btn-outline-primary" style="font-weight:600; border-radius:8px;" id="addContactBtn"><i class="uil-plus"></i> Add Contact Card</button>
                            </div>
                            <div class="card-body p-4" style="background: #f8fafc;">
                                <div id="contactsContainer">
                                    @forelse($dynamicContacts as $index => $contact)
                                        <div class="card mb-3 contact-row border shadow-sm" data-index="{{ $index }}" style="border-radius:10px;">
                                            <div class="card-body p-3">
                                                <div class="row align-items-center">
                                                    <div class="col-md-3">
                                                        <label class="form-label text-muted fw-bold" style="font-size:0.85rem;">Label (e.g. Phone, Email)</label>
                                                        <input type="text" name="contacts[{{ $index }}][label]" class="form-control" value="{{ $contact['label'] ?? '' }}" placeholder="Support Number" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label text-muted fw-bold" style="font-size:0.85rem;">Value</label>
                                                        <input type="text" name="contacts[{{ $index }}][value]" class="form-control" value="{{ $contact['value'] ?? '' }}" placeholder="+xx xxxxxxxx" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="d-flex flex-column gap-2 mt-4 ml-2">
                                                            <div class="form-check form-switch m-0 d-flex align-items-center">
                                                                <input class="form-check-input m-0 me-2" type="checkbox" name="contacts[{{ $index }}][show_on_invoice]" value="1" {{ isset($contact['show_on_invoice']) && $contact['show_on_invoice'] ? 'checked' : '' }}>
                                                                <label class="form-check-label text-muted fw-bold" style="font-size:0.85rem; margin-top:2px;">Show on Invoice</label>
                                                            </div>
                                                            <div class="form-check form-switch m-0 d-flex align-items-center">
                                                                <input class="form-check-input m-0 me-2" type="checkbox" name="contacts[{{ $index }}][show_label]" value="1" {{ isset($contact['show_label']) && $contact['show_label'] ? 'checked' : '' }}>
                                                                <label class="form-check-label text-muted fw-bold" style="font-size:0.85rem; margin-top:2px;">Include Label (Not just value)</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 text-center mt-3">
                                                        <button type="button" class="btn btn-sm btn-soft-danger px-2 mt-1 remove-contact"><i class="uil-trash-alt" style="font-size:1.2rem;"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <!-- No contacts yet -->
                                    @endforelse
                                </div>
                                
                                <div class="text-center p-4 mt-2 border rounded" id="noContactsMsg" style="border-style:dashed!important; background:#fff; display: {{ count($dynamicContacts) == 0 ? 'block' : 'none' }}">
                                    <i class="uil-phone-slash" style="font-size: 2rem; color: #cbd5e1;"></i>
                                    <h6 class="text-muted mt-2">No contact cards available.</h6>
                                    <small class="text-muted">Click "Add Contact Card" to create multiple flexible network fields.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Branding Logos -->
                        <div class="tab-pane fade" id="v-pills-brand" role="tabpanel" aria-labelledby="v-pills-brand-tab"
                            tabindex="0">
                            <div class="row">
                                <!-- Main App Logo -->
                                <div class="col-md-6 mb-4">
                                    <div class="custom-card h-100 mb-0">
                                        <div class="card-header-modern">Primary Logo (App)</div>
                                        <div class="card-body p-4 text-center">
                                            <div class="img-preview-box" id="liveMainLogoContainer"
                                                style="{{ $appLogoUrl ? '' : 'display:none;' }}">
                                                <img src="{{ $appLogoUrl ? url($appLogoUrl) : '' }}" alt="App Logo"
                                                    id="liveMainLogo">
                                            </div>
                                            <div
                                                class="upload-box d-flex flex-column align-items-center justify-content-center">
                                                <i class="uil-cloud-upload upload-icon"></i>
                                                <h6 class="mb-1 text-dark">Click or drag image here</h6>
                                                <small class="text-muted">High resolution, transparent PNG
                                                    recommended.</small>
                                                <input type="file" id="logoFileInput" accept="image/*">
                                            </div>
                                            <input type="hidden" name="logo" id="logoData">
                                            <div class="w-100 text-center">
                                                <div class="floating-success shadow-sm" id="logoSuccess"
                                                    style="display:none;"><i class="uil-check-circle me-1"></i> Ready to
                                                    save!</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Invoice Logo -->
                                <div class="col-md-6 mb-4">
                                    <div class="custom-card h-100 mb-0">
                                        <div class="card-header-modern d-flex justify-content-between align-items-center">
                                            <span>Invoice Logo</span>
                                        </div>
                                        <div class="card-body p-4 text-center">
                                            <div class="form-check form-switch mb-4 d-flex align-items-center justify-content-center gap-2"
                                                style="background: #f8fafc; padding: 10px; border-radius: 8px;">
                                                <input class="form-check-input m-0" type="checkbox" role="switch"
                                                    name="same_as_logo" value="1" id="sameAsLogo"
                                                    style="width:40px;height:20px; cursor:pointer;" checked>
                                                <label class="form-check-label fw-bold" for="sameAsLogo"
                                                    style="cursor:pointer; color: #475569;">Use Primary Logo for
                                                    Invoice</label>
                                            </div>

                                            <div id="invoiceLogoUploadArea"
                                                style="opacity: 0.4; pointer-events: none; transition: all 0.3s ease;">
                                                <div class="img-preview-box" id="currentInvoiceLogoContainer"
                                                    style="{{ $invoiceLogoUrl ? '' : 'display:none;' }}">
                                                    <img src="{{ $invoiceLogoUrl ? url($invoiceLogoUrl) : '' }}"
                                                        alt="Invoice Logo" id="liveInvoiceLogo">
                                                </div>

                                                <div
                                                    class="upload-box d-flex flex-column align-items-center justify-content-center">
                                                    <i class="uil-receipt upload-icon"></i>
                                                    <h6 class="mb-1 text-dark">Click to upload invoice logo</h6>
                                                    <small class="text-muted">Often a darker or monochrome version.</small>
                                                    <input type="file" id="invoiceLogoFileInput" accept="image/*">
                                                </div>
                                                <input type="hidden" name="invoice_logo" id="invoiceLogoData">
                                                <div class="w-100 text-center">
                                                    <div class="floating-success shadow-sm" id="invoiceLogoSuccess"
                                                        style="display:none;"><i class="uil-check-circle me-1"></i> Invoice
                                                        logo ready!</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="custom-card mt-3 mb-5 p-3"
                        style="background: transparent; box-shadow: none; border-top: 1px solid #e2e8f0; border-radius: 0;">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn-modern"><i class="uil-save me-1"></i> Save All Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Cropper Modal -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:16px; overflow:hidden; border:none; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background:#fff; border-bottom:1px solid #e2e8f0; padding: 20px 24px;">
                    <h5 class="modal-title fw-bold" id="cropperModalLabel" style="color:#1e293b;"><i
                            class="uil-crop-alt me-2"></i>Adjust Image Layout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="background:#0f172a;">
                    <div class="img-container w-100 d-flex justify-content-center"
                        style="height: 480px; display:flex; align-items:center;">
                        <img id="imageToCrop" src="" style="max-height: 100%; max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer" style="background:#fff; border-top:1px solid #e2e8f0; padding: 16px 24px;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        style="border-radius:8px; font-weight: 500;">Cancel</button>
                    <button type="button" class="btn-modern disabled" id="cropBtn" style="padding: 10px 30px;"><i
                            class="uil-check me-1"></i> Confirm Crop</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let cropper;
            let currentInputType = '';
            const imageToCrop = document.getElementById('imageToCrop');
            let cropperModalElement = document.getElementById('cropperModal');
            let cropperModal = new bootstrap.Modal(cropperModalElement);
            const cropBtn = document.getElementById('cropBtn');

            // Toggle Invoice Logo Upload area gracefully
            const sameAsLogo = document.getElementById('sameAsLogo');
            const uploadArea = document.getElementById('invoiceLogoUploadArea');

            function handleInvoiceToggle() {
                if (sameAsLogo.checked) {
                    uploadArea.style.opacity = '0.4';
                    uploadArea.style.pointerEvents = 'none';
                    document.getElementById('invoiceLogoData').value = '';
                } else {
                    uploadArea.style.opacity = '1';
                    uploadArea.style.pointerEvents = 'auto';
                }
            }
            sameAsLogo.addEventListener('change', handleInvoiceToggle);
            handleInvoiceToggle(); // Run on init

            // Initialize file select for Cropper
            function setupCropperLogic(fileInputId, type) {
                document.getElementById(fileInputId).addEventListener('change', function (e) {
                    const files = e.target.files;
                    if (files && files.length > 0) {
                        const file = files[0];
                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire({
                                title: 'File Too Large!',
                                text: 'Please select an image smaller than 2MB.',
                                icon: 'warning',
                                confirmButtonColor: '#ef4444'
                            });
                            this.value = ''; // Reset
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function (event) {
                            imageToCrop.src = event.target.result;
                            currentInputType = type;
                            cropBtn.classList.remove('disabled');
                            cropperModal.show();
                        };
                        reader.readAsDataURL(file);
                        this.value = ''; // Reset
                    }
                });
            }

            setupCropperLogic('logoFileInput', 'logo');
            setupCropperLogic('invoiceLogoFileInput', 'invoice_logo');

            cropperModalElement.addEventListener('shown.bs.modal', function () {
                if (cropper) { cropper.destroy(); }
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: NaN,
                    viewMode: 2,
                    autoCropArea: 0.8,
                    background: false,
                    zoomable: true
                });
            });

            cropperModalElement.addEventListener('hidden.bs.modal', function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                cropBtn.classList.add('disabled');
            });

            cropBtn.addEventListener('click', function () {
                if (!cropper) return;
                // Generate base64 (Auto scale down exceptionally large logos)
                const canvas = cropper.getCroppedCanvas({
                    maxWidth: 800,
                    maxHeight: 800,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });
                const base64Img = canvas.toDataURL('image/png');

                if (currentInputType === 'logo') {
                    document.getElementById('logoData').value = base64Img;

                    // Show preview immediately
                    document.getElementById('liveMainLogo').src = base64Img;
                    document.getElementById('liveMainLogoContainer').style.display = 'block';

                    const s = document.getElementById('logoSuccess');
                    s.style.display = 'inline-block';
                    setTimeout(() => { s.style.opacity = '1'; }, 50);
                } else if (currentInputType === 'invoice_logo') {
                    document.getElementById('invoiceLogoData').value = base64Img;

                    // Show preview immediately
                    document.getElementById('liveInvoiceLogo').src = base64Img;
                    document.getElementById('currentInvoiceLogoContainer').style.display = 'block';

                    const is = document.getElementById('invoiceLogoSuccess');
                    is.style.display = 'inline-block';
                    setTimeout(() => { is.style.opacity = '1'; }, 50);
                }

                cropperModal.hide();
            });

            // AJAX Form Submission
            document.getElementById('settingsForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...';
                btn.classList.add('disabled');

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('disabled');

                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#6366f1'
                            });
                        } else {
                            Swal.fire('Error!', data.message || 'Something went wrong while saving.', 'error');
                        }
                    })
                    .catch(error => {
                        btn.innerHTML = originalText;
                        btn.classList.remove('disabled');
                        console.error(error);
                        Swal.fire('Failed!', 'Unable to process your request. Check console for details.', 'error');
                    });
            });

            // Dynamic Contacts Repeater Logic
            let contactIdx = {{ count($dynamicContacts) }};
            
            document.getElementById('addContactBtn').addEventListener('click', function () {
                document.getElementById('noContactsMsg').style.display = 'none';
                const container = document.getElementById('contactsContainer');

                let html = `
                    <div class="card mb-3 contact-row border shadow-sm" data-index="${contactIdx}" style="border-radius:10px;">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label text-muted fw-bold" style="font-size:0.85rem;">Label (e.g. Phone, Email)</label>
                                    <input type="text" name="contacts[${contactIdx}][label]" class="form-control" placeholder="Support Number" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted fw-bold" style="font-size:0.85rem;">Value</label>
                                    <input type="text" name="contacts[${contactIdx}][value]" class="form-control" placeholder="+xx xxxxxxxx" required>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column gap-2 mt-4 ml-2">
                                        <div class="form-check form-switch m-0 d-flex align-items-center">
                                            <input class="form-check-input m-0 me-2" type="checkbox" name="contacts[${contactIdx}][show_on_invoice]" value="1" checked>
                                            <label class="form-check-label text-muted fw-bold" style="font-size:0.85rem; margin-top:2px;">Show on Invoice</label>
                                        </div>
                                        <div class="form-check form-switch m-0 d-flex align-items-center">
                                            <input class="form-check-input m-0 me-2" type="checkbox" name="contacts[${contactIdx}][show_label]" value="1" checked>
                                            <label class="form-check-label text-muted fw-bold" style="font-size:0.85rem; margin-top:2px;">Include Label (Not just value)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center mt-3">
                                    <button type="button" class="btn btn-sm btn-soft-danger px-2 mt-1 remove-contact"><i class="uil-trash-alt" style="font-size:1.2rem;"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>`;

                container.insertAdjacentHTML('beforeend', html);
                contactIdx++;
            });

            document.getElementById('contactsContainer').addEventListener('click', function (e) {
                if (e.target.closest('.remove-contact')) {
                    e.target.closest('.contact-row').remove();
                    if (document.querySelectorAll('.contact-row').length === 0) {
                        document.getElementById('noContactsMsg').style.display = 'block';
                    }
                }
            });

        });
    </script>
@endsection