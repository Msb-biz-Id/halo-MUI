<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="text-center mb-5">❓ Frequently Asked Questions</h2>
            
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            What is Halal Certification?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Halal certification is an official recognition that a product meets Islamic dietary laws and is permissible for Muslim consumption.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            How long does the certification process take?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            The process typically takes 2-4 weeks, depending on the complexity of the product and inspection requirements.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            What documents are required?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Required documents include: company registration, product ingredients list, manufacturing process flow, and product samples.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            How much does certification cost?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Certification fees vary depending on product category, company size, and complexity. Contact us for a detailed quote.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            How long is the certificate valid?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Halal certificates are typically valid for 2 years, after which renewal is required.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="<?= url('/certificate/apply') ?>" class="btn btn-primary btn-lg me-2">
                    <i class="uil-plus-circle"></i> Apply Now
                </a>
                <a href="<?= url('/contact') ?>" class="btn btn-outline-secondary btn-lg">
                    <i class="uil-envelope"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
