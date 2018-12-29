@extends('layouts.default')
@section('content')

<div class="privacy-policy">
	<h1>Privacy Policy of AccessLocator</h1>

	<h2>Personal Data is collected for the following purposes:</h2>

	<h3>Facebook</h3>
	<p>When signing up using facebook, we collect your email address, first name, last name, zip code.</p>

	<h3>Google</h3>
	<p>When signing up using Google, we collect your email address, first name, last name, zip code, region, and country.</p>

	<h2>Analytics</h2>
	<p>We collect information through Google Analytics.</p>

	<h2>Owner and Data Controller</h2>
	<p>Josh Greig - 1440 Howard Avenue, Windsor, ON, Canada N8X 3T3</p>
	<p><strong>Owner contact email:</strong> {{env('SEND_EMAIL_ID')}}</p>

	<h2>Mode and place of processing the Data</h2>

	<h3>Methods of processing</h3>
	<p>The Owner takes appropriate security measures to prevent unauthorized access, disclosure, modification, or unauthorized destruction of the Data.
	The Data processing is carried out using computers and/or IT enabled tools, following organizational procedures and modes 
	strictly related to the purposes indicated. In addition to the Owner, in some cases, the Data may be accessible to 
	certain types of persons in charge, involved with the operation of AccessLocator (administration, sales, marketing, 
	legal, system administration) or external parties (such as third-party technical service providers, mail carriers, 
	hosting providers, IT companies, communications agencies) appointed, if necessary, as Data Processors by the Owner. 
	The updated list of these parties may be requested from the Owner at any time.</p>

	<h3>Legal basis of processing</h3>
	<p>The Owner may process Personal Data relating to Users if one of the following applies:</p>
	<ul>
		<li>Users have given their consent for one or more specific purposes. Note: Under some legislations the Owner may be allowed to process 
		Personal Data until the User objects to such processing ("opt-out"), without having to rely on consent or any other of the following legal bases. 
		This, however, does not apply, whenever the processing of Personal Data is subject to European data protection law;</li>
		<li>provision of Data is necessary for the performance of an agreement with the User and/or for any pre-contractual obligations thereof;</li>
		<li>processing is necessary for compliance with a legal obligation to which the Owner is subject;</li>
		<li>processing is related to a task that is carried out in the public interest or in the exercise of official authority vested in the Owner;</li>
		<li>processing is necessary for the purposes of the legitimate interests pursued by the Owner or by a third party.</li>
	</ul>
	<p>In any case, the Owner will gladly help to clarify the specific legal basis that applies to the processing, and in particular whether the 
	provision of Personal Data is a statutory or contractual requirement, or a requirement necessary to enter into a contract.</p>
	
	<h3>Retention time</h3>
	<p>Personal Data shall be processed and stored for as long as required by the purpose they have been collected for.</p>
	<p>Therefore:</p>
	<ul>
		<li>Personal Data collected for purposes related to the performance of a contract between the Owner and the User shall be retained until such contract has been fully performed.</li>
		<li>Personal Data collected for the purposes of the Owner’s legitimate interests shall be retained as long as needed to fulfill such purposes. Users may find specific 
		information regarding the legitimate interests pursued by the Owner within the relevant sections of this document or by contacting the Owner.</li>
	</ul>
	<p>The Owner may be allowed to retain Personal Data for a longer period whenever the User has given consent to such processing, as long as such consent is not withdrawn. 
	Furthermore, the Owner may be obliged to retain Personal Data for a longer period whenever required to do so for the performance of a legal obligation or 
	upon order of an authority.</p>
	<p>Once the retention period expires, Personal Data shall be deleted. Therefore, the right to access, the right to erasure, the right to 
	rectification and the right to data portability cannot be enforced after expiration of the retention period.</p>

	<div class="detailed-personal-data">
		<h2>Detailed information on the processing of Personal Data</h2>

		<div class="policy-box">
			<h3>Access to third party accounts</h3>
			<p>This type of service allows AccessLocator to access Data from your account on a third party service and 
			perform actions with it.</p>
			<p>These services are not activated automatically, but require explicit authorization by the User.</p>

			<h4>Facebook account access</h4>
			<p>This service allows AccessLocator to connect with the User's account on the 
			Facebook social network, provided by Facebook, Inc.</p>
			<p>Permissions asked: Contact email.</p>
			<p>Place of processing: US – <a href="https://www.facebook.com/policy.php" 
			target="_blank" 
			rel="noopener noreferrer">Privacy Policy</a></p>
		</div>

		<div class="policy-box">
			<h3>Analytics</h3>
			The services contained in this section enable the Owner to monitor and analyze web traffic and can be used to keep track of User behavior.

			<h4>Google Analytics (Google Inc.)</h4>
			<p>Google Analytics is a web analysis service provided by Google Inc. ("Google"). Google utilizes the Data collected to track and examine the use of AccessLocator, to prepare reports on its activities and share them with other Google services.
			Google may use the Data collected to contextualize and personalize the ads of its own advertising network.
			Personal Data collected: Cookies and Usage Data.</p>

			<p>Place of processing: US - 
				<a href="https://www.google.com/intl/en/policies/privacy/" target="_blank" rel="noopener noreferrer">Privacy Policy</a> - 
				<a href="https://tools.google.com/dlpage/gaoptout?hl=en" 
			onclick="javascript:return tryFunc('tryGaOptOut',{href:'https://tools.google.com/dlpage/gaoptout?hl=en'})" 
			target="_blank">Opt Out</a>.
			</p>
		</div>
		<div class="policy-box">
			<h3>Contact form (AccessLocator)</h3>
			<p>By filling in the contact form with their Data, the User authorizes AccessLocator to use these 
			details to reply to requests for information, quotes or any other kind of request as 
			indicated by the form's header.</p>
			<p>Personal Data collected: email address.</p>
		</div>
	</div>

	<h2>The rights of Users</h2>
	<p>Users may exercise certain rights regarding their Data processed by the Owner.</p>

	<p>In particular, Users have the right to do the following:</p>
	<ul>
		<li><strong>Withdraw their consent at any time.</strong> Users have the right to withdraw consent where they have previously 
		given their consent to the processing of their Personal Data.</li>
		<li><strong>Object to processing of their Data.</strong> Users have the right to object to the processing of their Data if 
		the processing is carried out on a legal basis other than consent. Further details are provided in the dedicated section below.</li>
		<li><strong>Access their Data.</strong> Users have the right to learn if Data is being processed by the Owner, obtain disclosure regarding 
certain aspects of the processing and obtain a copy of the Data undergoing processing.</li>
		<li><strong>Verify and seek rectification.</strong> Users have the right to verify the accuracy of their Data and ask for it to be updated or corrected.</li>
		<li><strong>Restrict the processing of their Data.</strong> Users have the right, under certain circumstances, to restrict the processing of their Data. 
In this case, the Owner will not process their Data for any purpose other than storing it.</li>
		<li><strong>Have their Personal Data deleted or otherwise removed.</strong> Users have the right, under certain circumstances, to obtain 
the erasure of their Data from the Owner.</li>
		<li><strong>Receive their Data and have it transferred to another controller.</strong> Users have the right to receive their Data in a structured, 
commonly used and machine readable format and, if technically feasible, to have it transmitted to another controller without any hindrance. This provision is applicable provided that the Data is processed by automated means and that the processing is based on the User's consent, on a contract which the User is part of or on pre-contractual obligations thereof.
		<li><strong>Lodge a complaint.</strong> Users have the right to bring a claim before their competent data protection authority.</li>
	</ul>
	<h3>Details about the right to object to processing</h3>
	<p>Where Personal Data is processed for a public interest, in the exercise of an official authority vested in the Owner or for the purposes of the 
legitimate interests pursued by the Owner, 
Users may object to such processing by providing a ground related to their particular situation to justify the objection.</p>

<h3>How to exercise these rights</h3>
<p>Any requests to exercise User rights can be directed to the Owner through the contact details provided in this document. 
These requests can be exercised free of charge and will be addressed by the Owner as early as possible and always within one month.</p>

	<h2>Additional information about Data collection and processing</h2>
	<h3>Legal action</h3>
	<p>The User's Personal Data may be used for legal purposes by the Owner in Court or in the stages leading to possible legal action arising from improper use of AccessLocator or the related Services.</p>
	<p>The User declares to be aware that the Owner may be required to reveal personal data upon request of public authorities.</p>

	<h3>System logs and maintenance</h3>
	<p>For operation and maintenance purposes, AccessLocator and any third-party services may collect files 
	that record interaction with AccessLocator (System logs) use other Personal Data (such as the IP Address) for this purpose.</p>

	<h3>Information not contained in this policy</h3>
	<p>More details concerning the collection or processing of Personal Data may be requested from the Owner at any time. Please see the contact information at the beginning of this document.</p>

	<h3>Changes to this privacy policy</h3>
	<p>The Owner reserves the right to make changes to this privacy policy at any time by giving notice to its Users on this page and possibly 
	within AccessLocator and/or - as far as technically and legally feasible - sending a notice to Users via any contact information available to the Owner. 
	It is strongly recommended to check this page often, referring to the date of the last modification listed at the bottom. </p>
	<p>Should the changes affect processing activities performed on the basis of the User's consent, the Owner shall collect new consent from the User, where required. </p>

	<div class="legal-definitions policy-box">
		<h2>Legal Definitions</h2>
		<dl>
			<dt>AccessLocator (or this Application)</dt>
			<dd>The means by which the Personal Data of the User is collected and processed.</dd>
			<dt>Cookies</dt>
			<dd>Small piece of data stored in the User's device.</dd>
			<dt>Personal Data (or Data)</dt>
			<dd>Any information that directly, indirectly, or in connection with other information 
			- including a personal identification number - allows for the identification or identifiability 
			of a natural person.</dd>
			<dt>Data Controller (or Owner)</dt>
			<dd>The natural or legal person, public authority, agency or other body which, alone or jointly with others, determines the purposes and means of 
			the processing of Personal Data, including the security measures concerning the operation and use of AccessLocator. 
			The Data Controller, unless otherwise specified, is the Owner of AccessLocator.
			</dd>
			<dt>Data Processor (or Data Supervisor)</dt>
			<dd>The natural or legal person, public authority, agency or other body which processes Personal Data on behalf of 
	the Controller, as described in this privacy policy.</dd>

			<dt>European Union (or EU)<dt>
			<dd>Unless otherwise specified, all references made within this document to the European Union include all 
			current member states to the European Union and the European Economic Area.</dd>
			<dt>Service</dt>
			<dd>The service provided by AccessLocator as described in the relative terms (if available) and on this site/application.</dd>
		</dl>
		
		<h3>Legal information</h3>
		<p>This privacy statement has been prepared based on provisions of multiple legislations, including Art. 13/14 of Regulation (EU) 2016/679 (General Data Protection Regulation).
	This privacy policy relates solely to AccessLocator, if not stated otherwise within this document.</p>
	</div>
	
	<p>Latest update: December 21, 2018</p>
</div>

@stop