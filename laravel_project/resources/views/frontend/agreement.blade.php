@extends('frontend.layouts.app')

@section('styles')
@endsection

@section('content')

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: {{ $site_innerpage_header_background_color }};">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image) }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;">
    @endif

        @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            <div data-youtube="{{ $site_innerpage_header_background_youtube_video }}"></div>
        @endif

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">

                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                    <div class="row justify-content-center mt-5">
                        <div class="col-md-12 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('BETA CONTENT CREATOR AGREEMENT') }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">

            <div class="row mb-5">
                <div class="col-md-12">
                    <p>This Content Creator Agreement ("Agreement") is entered into as of _______, 20__ (the "Effective Date") by Coaches HQ LLC., a Virginia corporation, ("Coaches HQ LLC"), and ________ ("Creator") and includes the following terms and conditions and Exhibit A attached hereto.</p>
                    <p><strong>1. <u>Services & Rights.</u></strong></p>
                    <p>1.1. <u>Services; Content.</u> Creator shall perform the services and provide the content as specified in the "Statement of Work" attached hereto as Exhibit A (such services and content, including all related (i) services provided hereunder and (ii) content and materials provided hereunder, are the "Services" and "Content", respectively).  Creator is solely responsible for all Content.</p>
                    <p>1.2. <u>Creator Biography and Likeness.</u> Creator hereby grants to Coaches HQ LLC a right and license, but no obligation, to use and disseminate Creator's name, likeness and biographical information, for promotional and other purposes.</p>
                    <p>1.3. <u>Ownership.</u> Creator retains all right, title, and interest in and to all Content provided under this Agreement.  However, by submitting Content pursuant to this Agreement, Creator grants to Coaches HQ LLC a worldwide, non, royalty free, license to make, display, perform, use, reproduce, distribute, license, sell, import, export, transmit, and to create derivatives, enhancements, extensions, improvements, and modifications of ("Derivatives"), provide user access to, and otherwise commercialize, in all manner and medium now or hereafter known, the Content, any Content IP and any Derivatives created pursuant to this Agreement.</p>
                    <p>1.4. <u>Content IP.</u> Creator will retain no rights of outcomes, IP obtained and generated in consequence of engaging in the content, IP obtained from utilizing content in association with data science to derive certain outcomes, measurements, analytics associated with engaging in Content, and any other Content IP similar rights.</p>
                    <p><strong>2. <u>Compensation.</u></strong> Subject to terms and conditions of this Agreement, Coaches HQ LLC will not provide monetary compensation directly or indirectly for the service provided through this agreement. Creator agrees to release Coaches HQ LLC to any and all obligation regarding compensation.</p>
                    <p><strong>3. <u>Term & Termination.</u></strong> This Agreement shall commence on the Effective Date and continue for a minimum of two (2) years, unless earlier terminated by either party for any or no reason upon sixty (60) days prior written notice to the other party.  Sections 1, 3, 4, 5 and 6 of this Agreement will survive termination of this Agreement.</p>
                    <p><strong>4. <u>Warranties and Indemnity.</u></strong> Creator represents, warrants and covenants that:  (i) Creator has all right, power and authority to enter into and properly perform under this Agreement; (ii) the Content is original to Creator; (iii) the Content shall not infringe upon the rights of any third party, including but not limited to trademark, copyright, trade secret, the rights of publicity and privacy and the right against defamation; and (iv) the Content complies with all applicable federal, state and local laws, rules and regulations.    Creator shall indemnify, defend and hold harmless Coaches HQ LLC against all damages, claims, liabilities, losses and other expenses that arise out of Creator's breach of any representation or warranty or other provision of this Agreement.</p>
                    <p><strong>5. <u>Limitation of Liability.</u></strong> IN NO EVENT SHALL Coaches HQ LLC BE LIABLE TO CREATOR UNDER ANY LEGAL OR EQUITABLE THEORY FOR: (I) ANY SPECIAL, INDIRECT OR CONSEQUENTIAL DAMAGES OR (II) ANY AMOUNT IN EXCESS OF $100.</p>
                    <p><strong>6. <u>Miscellaneous.</u></strong>  Notwithstanding any provision hereof, for all purposes of this Agreement each party shall be and act as an independent contractor and not as partner, joint venturer, employer, employee or agent of the other.  The Compensation payable to Creator is inclusive of, and Creator shall be solely responsible for, all tax obligations due to all taxing authorities arising from or in connection with amounts paid to Creator hereunder, including, without limitation, federal, state, and local withholding taxes, FICA, FUTA, Social Security, Medicare, SUI and other such taxes and deductions ("Taxes"). This Agreement and the rights, obligations and licenses herein, shall be binding upon, and inure to the benefit of, the parties hereto and their respective heirs, successors and assigns. Creator shall not assign or transfer this Agreement in whole or part without the prior written consent of Coaches HQ LLC.  Coaches HQ LLC may freely assign or transfer this Agreement in whole or part.  This Agreement contains the entire understanding of the parties regarding its subject matter and supersedes all other agreements and understandings, whether oral or written. No changes or modifications or waivers are to be made to this Agreement unless evidenced in writing and signed for and on behalf of both parties.  If any portion of this Agreement is held to be illegal or unenforceable, that portion shall be restated, eliminated or limited to the minimum extent necessary so that this Agreement shall reflect as nearly as possible the original intention of the parties and the remainder of this Agreement shall remain in full force and effect.  This Agreement shall be governed by and construed in accordance with the laws of the State of Virginia without regard to the conflicts of laws provisions thereof.</p>
                </div>
                <div class="col-md-12">
                    <p>Coaches HQ LLC TECHNOLOGIES, INC.</p>
                    <p>Name: Title: </p>
                </div>
                <div class="col-md-12">
                    <p class="text-center"><strong>EXHIBIT A</strong></p>
                    <p class="text-center"><strong>Statement of Work</strong></p>
                    <p>1. Description of Services:</p>
                    <p>Creator will submit the following to complete their Coaching Profile:</p>
                    <p>a. Introduction Information</p>
                    <p>b. Bio in written form subject to be edited for length and applicability</p>
                    <p>c. Headshot</p>
                    <p>d. Contact information including email address, website, Instagram handle, Linkedin Profile, Twitter Handle, etc.</p>
                    <p>e. Minimum of 10 Coaching Content pieces (blogs, videos, white papers, etc.)</p>
                    <p>f. Feedback directly to Coaches HQ Development team through Telegram channel</p>
                    <p>g. Participation in a minimum of 2 virtual zoom meetings within a 90 day period</p>
                    <p>2. Compensation:</p>
                    <p>In exchange for content, Coaches HQ LLC agrees to release all revenue associated with business generated from the exposure of being on the platform where employees as well as employers will have direct access to contact you as a coach.</p>
                </div>
                <div class="col-md-12">
                    <p class="text-center"><strong>MUTUAL NONDISCLOSURE AGREEMENT</strong></p>
                    <p>Each undersigned party (the "<u>Receiving Party</u>") understands that the other party  (the "<u>Disclosing Party</u>") has disclosed or may disclose information relating to the Disclosing  Party's business (including, without limitation, computer programs, technical drawings,  algorithms, know-how, formulas, processes, ideas, inventions (whether patentable or not),  schematics and other technical, business, financial, customer and product development plans,  forecasts, strategies and information), which to the extent previously, presently or subsequently  disclosed to the Receiving Party is hereinafter referred to as "Proprietary Information" of the  Disclosing Party.</p>
                    <p>In consideration of the parties discussions and any access of the Receiving Party  to Proprietary Information of the Disclosing Party, the Receiving Party hereby agrees as follows:</p>
                    <p>1. The Receiving Party agrees (i) to hold the Disclosing Party's Proprietary Information in confidence and to take reasonable precautions to protect such Proprietary  Information (including, without limitation, all precautions the Receiving Party employs with  respect to its own confidential materials), (ii) not to divulge any such Proprietary Information or  any information derived therefrom to any third person, (iii) not to make any use whatsoever at any  time of such Proprietary Information except to evaluate internally its relationship with the  Disclosing Party, (iv) not to copy or reverse engineer any such Proprietary Information and (v) not  to export or reexport (within the meaning of U.S. or other export control laws or regulations) any  such Proprietary Information or product thereof. If the receiving party is an organization, then the  Receiving Party also agrees that, even within Receiving Party, Proprietary Information will be  disseminated only to those employees, officers and directors with a clear and well-defined "need to know" for purposes of the business relationship between the parties. Without granting any right  or license, the Disclosing Party agrees that the foregoing shall not apply with respect to any  information after five years following the disclosure thereof or any information that the Receiving  Party can document (i) is or becomes (through no improper action or inaction by the Receiving  Party or any affiliate, agent, consultant or employee of the Receiving Party) generally available to  the public, or (ii) was in its possession or known by it without restriction prior to receipt from the  Disclosing Party, or (iii) was rightfully disclosed to it by a third party without restriction, or  (iv) was independently developed without use of any Proprietary Information of the Disclosing Party. The Receiving Party may make disclosures required by law or court order provided the Receiving Party uses diligent reasonable efforts to limit disclosure and to obtain confidential treatment or a protective order and allows the Disclosing Party to participate in the proceeding</p>
                    <p>2. Immediately upon a request by the Disclosing Party at any time, the Receiving Party will turn over to the Disclosing Party all Proprietary Information of the Disclosing  Party and all documents or media containing any such Proprietary Information and any and all  copies or extracts thereof. The Receiving Party understands that nothing herein (i) requires the  disclosure of any Proprietary Information of the Disclosing Party or (ii) requires the Disclosing  Party to proceed with any transaction or relationship.</p>
                    <p>3. This Agreement applies only to disclosures made before the second anniversary of this Agreement. The Receiving Party acknowledges and agrees that due to the unique nature of the Disclosing Party's Proprietary Information, there can be no adequate remedy  at law for any breach of its obligations hereunder, which breach may result in irreparable harm to  the Disclosing Party, and therefore, that upon any such breach or any threat thereof, the Disclosing  Party shall be entitled to appropriate equitable relief, without the requirement of posting a bond,  in addition to whatever remedies it might have at law. In the event that any of the provisions of  this Agreement shall be held by a court or other tribunal of competent jurisdiction to be illegal,  invalid or unenforceable, such provisions shall be limited or eliminated to the minimum extent  necessary so that this Agreement shall otherwise remain in full force and effect. This Agreement  shall be governed by the laws of the State of California without regard to the conflicts of law  provisions thereof. This Agreement supersedes all prior discussions and writings and constitutes  the entire agreement between the parties with respect to the subject matter hereof. The prevailing  party in any action to enforce this Agreement shall be entitled to costs and attorneys' fees. No  waiver or modification of this Agreement will be binding upon a party unless made in writing and  signed by a duly authorized representative of such party and no failure or delay in enforcing any  right will be deemed a waiver.</p>
                </div>
                <div class="col-md-12">
                    <p>Date: </p>
                    <p>Coaches HQ LLC Technologies, Inc</p>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
            <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif
    <script>

        $(document).ready(function(){

            "use strict";

            @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            @endif

        });

    </script>

@endsection
