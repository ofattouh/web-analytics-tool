<?php

	/**
	 * MLITSD OSIR Survey Participant Report Template
	 * Author: Omar M.
	**/

	ob_start();
?>

<!-- MLITSD Participant Report CSS -->

<style>
	a, img {
  	background-color: transparent !important;
	}

	td.MLITSD-report-table-logo-text {
		font-size: 20px;
		font-weight: bold;
	}

  div.MLITSD-report-content {
    font-size: 14px;
    letter-spacing: 2px;
  }

	p.MLITSD-report-header {
		font-size: 25px;
		font-weight: bold;
		color: #000;
	}

	table.MLITSD-report-table, table.MLITSD-report-table td {
		border: 0px !important;
	}

	table.MLITSD-report-table {
		margin: 0px !important;
		width: 80% !important;
	}

	h3.MLITSD-report-announcement-header {
		text-transform: uppercase !important;
		color: #663399 !important;
		font-weight: 700;
	}

	p.footer-announcement {
		color: #663399;
		font-size: 18px;
		font-weight: bold;
	}
</style>

<!-- MLITSD Participant Report Logo -->

<table class="MLITSD-report-table" autosize="1">
	<tr>
		<td class="MLITSD-report-table-logo-text">
			Wellness and Mental Health In MLITSD
		</td>

		<td>
			<img src="https://osir-prod-uploads.s3.ca-central-1.amazonaws.com/wp-content/uploads/2023/01/31204316/MLITSD-Wellness-and-Mental-Health.png" 
				alt="MLITSD Wellness and Mental Health" />
		</td>
	</tr>
</table>

<!-- MLITSD Participant Report Seperator -->

<hr style="height:20px;border-width:0;color:#3CB371;background-color:#3CB371" />

<!-- MLITSD Participant Report Content -->

<div class="MLITSD-report-content">
	<p class="MLITSD-report-header">Thank you for your time and participation.</p>

	<p>
		Visit the <a href="https://trk.mmail.lst.fin.gov.on.ca/trk/click?ref=zr9uf3m5h_3-9c4bx312bfbx01197&" 
			target="_blank">MLITSD Wellness and Mental Health intranet page</a> to keep up to date on wellness 
		resources and supports as we move forward on our wellness journey.
	</p>
	
	<p>There you will find:</p>

	<p>
		<ul>
			<li>How to access MLITSD premium subscription to the <b>Calm App</b> available until October 31, 2023. Visit this link: <a href="https://www.calm.com/b2b/ontario-ministry-of-labour/subscribe" target="_blank">https://www.calm.com/b2b/ontario-ministry-of-labour/subscribe</a> to register</li>
			<li>All recorded webinars to assist you in starting or continuing your mental fitness journey. In addition to the foundational mental fitness webinars that support employees in building mental resilience, understand what charges and drains your battery and how to establish a personal mental fitness plan, there are several micro-skill recordings such as:
				<br>&nbsp;&nbsp;&nbsp;&nbsp;&#x2022;&nbsp;No More languishing, Introduction to Mind Tricks, Empathy for Leaders, and Purpose</li>
			<li>Employee and Family Assistance Program (EFAP) service offerings</li>
			<li>Key information on benefits</li>
			<li>Other regularly updated wellness resources</li>
		</ul>
	</p>

	<h3 class="MLITSD-report-announcement-header">New 2023/2024 Wellness Program</h3>

	<p>This year will focus on raising awareness on wellness issues. Based on the data and emerging issues which will consist of:</p>

	<div style="display:flex;">
		<div>
			<ol>
				<li>
					Hosting webinars bi-monthly that will be designed to:<br>
				
					<table class="MLITSD-report-table" autosize="1">
						<tbody>
							<tr>
								<td style="vertical-align:top">
									<img src="https://osir-prod-uploads.s3.ca-central-1.amazonaws.com/wp-content/uploads/2023/01/31204922/MLITSD-Check-Mark.png" 
										alt="MLITSD Check Mark" width="70" />
								</td>
								
								<td>
									<ul style="list-style-type: square;">
										<li>Build awareness on the issues</li>
										<li>Provide tools and resources</li>
										<li>Identify supports available</li>
									</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</li>
			
				<li>Bi-monthly tools and tips on addressing workload issues</li>
				<li>Leadership development, and</li>
				<li>Events and initiatives brought to you by the MLITSD Wellness Committee such as the annual ministry-wide virtual wellness fair</li>
			</ol>
		</div>

		<div class="MLITSD-report-focus-friday-div" style="float:right">
			<img src="https://osir-prod-uploads.s3.ca-central-1.amazonaws.com/wp-content/uploads/2023/01/31204654/MLITSD-Focus-Friday.png" 
				alt="MLITSD Focus Friday" />
		</div>
	</div>
	
	<p class="footer-announcement">Stay tuned for more details.</p>
</div>

<?php $participantReportMsg = ob_get_clean(); ?>
