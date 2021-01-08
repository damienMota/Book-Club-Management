# Book-Club-Management
Built an application management system for a Book Club that showcases the dynamic relationship between a company (implementation) and its applicants.<br>

To avoid any $_SESSION complications please use two windows to get the best experience, one for Implementation's side and one for the Client's side. Thank you!<br>

<div><b>Steps</b></div><br>
<b>1. Implementation Side</b>
<ul>
  <li>After logging in with the credentials provided in my resume you will land on the home page which will display three data tables: Pending, Submitted, Completed. 
  These tables will list applications with their corresponding status in the application process.</li>
  <li>Head over to "Initiate Application" on the top right. You will need to fill out all 4 text fields to properly initiate the application. Please provide an email you can access.</li>
  <li>After an application has been initiated, it will be stored in the Pending table.</li>
  <li>All three tables will contain an Activity Log which stores an insert made on every status change</li>
</ul>
<b>2. Client Side</b>
<ul>
  <li>Open your email inbox with the same email you provided.</li>
  <li>In an email, you will recieve a 6 digit code provided with a link to access your application portal. Please use this code to login in. </li>
  <li>Complete all three application pages and submit your application. You will recieve an email shortly after.</li>
</ul>
<b>3. Implementation Side</b>
<ul>
  <li>After an application has been Submitted, it will be stored in the Submitted table.</li>
  <li>You now have the option to click on the applicant's row on the Submitted table which brings up the "Edit Application" page and a copy of the clients application in a downloadble pdf. This feature is only available once an appplicaiton
  has been Submitted or Completed.</li>
  <li>The Edit Application page also provides options to change major text fields, return the application back to client in a new email and 
  mark the application complete.</li>
  <li>After reviewing the application, mark complete to finalize the application process. A confirmation email will be sent to the client.</li>
</ul>
