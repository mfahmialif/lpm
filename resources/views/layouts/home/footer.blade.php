 <!-- Footer -->
 <footer>
     <!-- Footer Main -->
     <div class="footer-main bg-contain"
         style="background-image: url({{ asset('home') }}/assets/img/footer/footer-bg-large.jpg)">
         <div class="footer-bottom">
             <div class="container">
                 <div class="row footer-bottom-row">
                     <div class="col-12 col-md-6 col-lg-6">
                         <div class="footer-copyright text text-16">
                             Copyright ©<span class="current-year"></span> {{ config('app.name') }}.
                             All rights reserved.
                         </div>
                     </div>
                     <div class="col-12 col-md-6 col-lg-6">
                         <ul class="footer-menu footer-policies list-unstyled">
                             <li>
                                 <a href="{{ route('privacy-policy.index') }}" class="text text-16 link" aria-label="Privacy Policy">
                                     Privacy Policy
                                 </a>
                             </li>
                             <li>
                                 <a href="{{ route('root.index') }}#faq" class="text text-16 link" aria-label="FAQ">
                                     Faqs
                                 </a>
                             </li>
                             <li>
                                 <a href="{{ route('contact.index') }}" class="text text-16 link" aria-label="FAQ">
                                     Contact
                                 </a>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </footer>
