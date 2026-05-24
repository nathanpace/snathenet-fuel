<hr/>
<div class="h4">About this search tool.</div>
<div class = "para">
    With the telephone network in the UK changing and telephone exchanges closing as the move to IP-based telephony continues, this tool is basically a final snaphot of the network as it existed in the mid 2020s, before things started to permamently change.<br/>
    The tool is based around a (MySQL) database of over 5600 UK telephone exchanges, created by me from an Excel spreadsheet of exchange information supplied by Ofcom and available on the web. From this spreadsheet, I was able to build up STD code information for each exchange and then augment this data further with extra information both relevant and historical to produce what you now see here.
</div>

<div class="h4">Search information available (click each heading to learn more)</div>
<div class="h5" id='about-code'>Code/Exchange Group</div>
<div id='about-code-text'>
    Searching for a current STD code or Charge Group will bring back the following:
    <ul>
        <li><b>STD Code</b> - the code searched for, or the code in the exchange group.</li>
        <li><b>STD Area Name</b> - the name given to the code area. This is also known as the <i>Exchange Group Name</i>.</li>
        <li><b>Number Ranges</b> - for certain codes, the first two or three digits of the number identify particular code areas in the code. These digits will be shown here.</li>
        <li><b>Exchange Count</b> - the number of exchanges in the STD area.</li>
        <li><b>Charge Group Name</b> - the (historical) charge group this code belongs to. Some charge groups cover more than one code.</li>
        <li><b>Charge Group ID</b> - the (historical) charge group ID.</li>
        <li><b>Previous Codes</b> - how the code has changed over the years, including phONEday or Big Number changes.</li>
        <li><b>Original Codes</b> - some codes were originally completely different, this field will show what they were originally created as.</li>
        <li><b>Code Mapping</b> - the original letter mapping of the code from when STD was first set up.</li>
        <li><b>Reason For Code Mapping</b> - how the mapping was determined for this code.</li>
        <li><b>Other Notes</b> - any other facts about this code.</li>
    </ul>
    Clicking on the row will show all the exchanges under that code, with the following information:
    <ul>
        <li><b>Previous STD code(s)</b> - the original STD code allocated to this exchange.</li>
        <li><b>Exchange Name(s)</b> - the name of the exchange. Some exchanges are known by more than one name.</li>
        <li><b>Exchange ID</b> - every exchange has a unique ID.</li>
        <li><b>Network Zone/Network District</b> - the exchange network is split into 9 zones and each zone is further split into districts.</li>
        <li><b>Postcode</b> - the postcode location of the exchange.</li>
    </ul>
</div>

<div class="h5" id='about-exchange'>Exchange</div>
<div id='about-exchange-text'>
    Searching for an exchange will bring back the following:
    <ul>
        <li><b>Previous STD code(s)</b> - the original STD code allocated to this exchange.</li>
        <li><b>Current STD code</b> - the current STD code of this exchange.</li>
        <li><b>Exchange Name(s)</b> - the name of the exchange. Some exchanges are known by more than one name.</li>
        <li><b>Exchange ID</b> - the unique ID of this exchange.</li>
        <li><b>Network Zone/Network District</b> - the network zone and district this exchange belongs to.</li>
        <li><b>Postcode</b> - the postcode location of the exchange.</li>
        <li><b>Code Sector</b> - for director exchanges, what sector the exchange belongs to.</li>
        <li><b>Pre-AFN exchange code</b> - for director exchanges, what the original code of the exchange was before migration to all-figure numbering.</li>
        <li><b>Post-AFN exchange code</b> - for director exchanges, the code of the exchange after all-figure numbering was implemented.</li>
        <li><b>AFN sector</b> - which sector the exchange was moved to after all-figure numbering.</li>
        <li><b>Additional Notes</b> - any other facts about this exchange.</li>
    </ul>
    Clicking on the current STD code for the exchange will perform a search on that code.
</div>

<div class="h5" id='about-historical'>Historical</div>
<div id='about-historical-text'>
    Searching for historical information will bring back the following:<br>
    A list of matching codes:<br/>
    <ul>
        <li><b>STD Code</b> - the code searched for, or the code in the exchange group.</li>
        <li><b>STD Area Name</b> - the name given to the code area. This is also known as the <i>Exchange Group Name</i>.</li>
        <li><b>Group Type</b> - the type of exchange group, will either be <i>Standard</i>, <i>Director</i>, <i>Core</i> or <i>Ring</i></li>
        <li><b>Code Mapping</b> - the original letter mapping of the code from when STD was first set up.</li>
        <li><b>Reason For Code Mapping</b> - how the mapping was determined for this code.</li>
        <li><b>Routing</b> - how calls to this code would have been routed.</li>
        <li><b>Code Moved From</b> - what the code was moved from.</li>
        <li><b>Code Moved To</b> - what the code was moved to.</li>            
        <li><b>Other Notes</b> - any other facts about this code.</li>
    </ul>
    A list of matching exchanges:<br/>
    <ul>
        <li><b>Previous STD code(s)</b> - the original STD code allocated to this exchange.</li>
        <li><b>Current STD code</b> - the current STD code of this exchange.</li>
        <li><b>Exchange Name(s)</b> - the name of the exchange. Some exchanges are known by more than one name.</li>
        <li><b>Exchange ID</b> - the unique ID of this exchange.</li>
        <li><b>Network Zone/Network District</b> - the network zone and district this exchange belongs to.</li>
        <li><b>Postcode</b> - the postcode location of the exchange.</li>
        <li><b>Code Sector</b> - for director exchanges, what sector the exchange belongs to.</li>
        <li><b>Pre-AFN exchange code</b> - for director exchanges, what the original code of the exchange was before migration to all-figure numbering.</li>
        <li><b>Post-AFN exchange code</b> - for director exchanges, the code of the exchange after all-figure numbering was implemented.</li>
        <li><b>AFN sector</b> - which sector the exchange was moved to after all-figure numbering.</li>
        <li><b>Additional Notes</b> - any other facts about this exchange.</li>
    </ul>
</div>
