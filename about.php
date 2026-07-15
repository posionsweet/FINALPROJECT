<?php
include 'config.php';
include 'header.php';
?>

<div class="max-w-3xl mx-auto space-y-8 py-4">
    <div class="bg-white border border-gray-100 shadow-sm rounded-xl p-8 text-center space-y-4">
        <span class="text-indigo-600 font-bold tracking-widest text-xs uppercase">Corporate Profile</span>
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">About Thread and Trend Inc.</h2>
        <p class="text-gray-500 text-sm leading-relaxed max-w-2xl mx-auto">
            Thread and Trend is a premium academic mockup e-commerce organization specializing in collegiate outerwear, custom button-downs, and minimalist lifestyle apparel. Built with native performance architectures using plain PHP data arrays.
        </p>
    </div>

    <div class="bg-white border border-gray-100 shadow-sm rounded-xl p-8">
        <h3 class="font-bold text-sm tracking-wider uppercase text-gray-400 text-center mb-6">Development Group Structure</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-extrabold text-sm flex items-center justify-center flex-shrink-0">LM</div>
                <div>
                    <span class="font-bold text-gray-800 text-sm">Lexus Medina</span>
                    <p class="text-xs text-gray-400 mt-1">Responsible for core backend system architecture, raw MySQL query compilation, and server deployment routines.</p>
                </div>
            </div>
            
            <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-extrabold text-sm flex items-center justify-center flex-shrink-0">JC</div>
                <div>
                    <span class="font-bold text-gray-800 text-sm">Jonas Crisostomo</span>
                    <p class="text-xs text-gray-400 mt-1">Full-stack database administrator in charge of relational schemas, security tracking logs, and session protection routines.</p>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-extrabold text-sm flex items-center justify-center flex-shrink-0">GC</div>
                <div>
                    <span class="font-bold text-gray-800 text-sm">Gabrielle Crisostomo</span>
                    <p class="text-xs text-gray-400 mt-1">Lead UX Designer specializing in wireframing, layout system design, and interface responsive consistency using Tailwind utilities.</p>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-extrabold text-sm flex items-center justify-center flex-shrink-0">JB</div>
                <div>
                    <span class="font-bold text-gray-800 text-sm">Joshua Biglete</span>
                    <p class="text-xs text-gray-400 mt-1">Operations and QA Auditor mapping user flows, form field arrays validation arrays, and system manual documentation structures.</p>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
include 'footer.php';
?>