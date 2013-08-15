<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a href="#pbcorehelp">Home</a></li>
  <li><a href="#minthelp">Profile</a></li>
  
</ul>
 
<div class="tab-content">
  <div class="tab-pane active" id="pbcorehelp">
	  <p><h2>Inventory Form Help</h2></p>

<p><b>Unique Identifier:</b> A unique identifier string for a particular instantiation of a media item. Best practice is to use an identification method that is in use within your agency, station, production company, office, or institution.</p>

<p><b>PBCore</b><br />
1.3 — formatIdentifier<br />
2.0 — instantiationIdentifier</p>

<p><b>Examples </b>(free form text entry)</p>

<p><b>•</b> Beta-SP 467–34</p>

<p><b>•</b> GlenCanyonDamSpillways.wmf</p>

<p><b>•</b> ISBN 0–07-135026–8</p>

<p><b>•</b> /public/items/PBCore_DC03PaperFinal.pdf</p>

<hr /><p><b>Identifier Source:</b> Used in conjunction with Unique Identifer. Provides not only a locator number, but also indicates an agency or institution who assigned it. Therefore, if your station or organization created this ID, enter in your station/organization name in this field. If the ID came from an outside entity or standards organization, enter the name of that entity here.</p>

<p><b>PBCore</b><br />
1.3 — formatIdentifierSource<br />
2.0 — instantiationIdentiferSource</p>

<p><b>Examples</b> (free form text entry)</p>

<p><b>•</b> Minnesota Public Radio</p>

<p><b>•</b> Central Intelligence Agency</p>

<p><b>•</b> Smith, Jedidiah</p>

<p><b>•</b> U.S. ISBN Agency</p>

<hr /><p><b>Media Type:</b> Identifies the general, high level nature of the content of a media item. It uses categories that show how content is presented to an observer, e.g., as a sound, text or moving image.</p>

<p><b>PBCore</b><br />
1.3 — formatMediaType<br />
2.0 — instantiationMediaType</p>

<p><b>Examples </b>(choose from picklist)</p>

<p><b>•</b> Moving Image</p>

<p><b>•</b> Sound</p>

<hr /><p><b>Physical Format:</b> The format of a particular version or rendition of a media item as it exists in an actual physical form.</p>

<p><b>PBCore</b><br />
1.3 — formatPhysical<br />
2.0 –instantiationPhysical</p>

<p><b>Examples</b> (use picklist):</p>

<p><b>•</b> 1 inch videotape</p>

<p><b>•</b> DVC-Pro 50</p>

<p><b>•</b> DVD-Video Disc</p>

<hr /><p><b>Digital Format: </b>Identifies the format of a particular rendition of a media item in its digital form. Digital media formats may be expressed with formal Internet MIME types.MIME types available at IANA:<br />
video:<a href="http://www.iana.org/assignments/media-types/video/index.html">http://www.iana.org/assignments/media-types/video/index.html</a><br />
audio:<a href="http://www.iana.org/assignments/media-types/audio/index.html">http://www.iana.org/assignments/media-types/audio/index.html</a></p>

<p><b>PBCore</b><br />
1.3 — formatDigital<br />
2.0 — instantiationDigital</p>

<p><b>Examples</b> (MIME type)</p>

<p><b>•</b> audio/mpeg</p>

<p><b>•</b> audio/x-aiff</p>

<p><b>•</b> video/H264</p>

<p><b>•</b> video/quicktime</p>

<hr /><p><b>Encoding:</b> Identifies how the actual information in a media item is compressed, interpreted, or formulated using a particular scheme.</p>

<p><b>PBCore</b><br />
1.3 — essenceTrackEncoding<br />
2.0 — essenceTrackEncoding</p>

<p><b>Examples </b>(use picklist)</p>

<p><b>•</b> Audio Interchange File Format (AIFF)</p>

<p><b>•</b> H.264/MPEG-4 AVC: SorensonAVC Pro codec</p>

<p><b>•</b> MJPEG: FFmpeg</p>

<p><b>•</b> MPEG-4: AAC</p>

<hr /><p><b>File Size: </b>Indicates the storage requirements or file size of a digital media item. Include your unit of measure (kB, MB, GB).</p>

<p><b>PBCore</b><br />
1.3 — formatFileSize<br />
2.0 — instantiationFileSize</p>

<p><b>Examples</b> (free form entry)</p>

<p><b>•</b> 125 kB</p>

<p><b>•</b> 322 MB</p>

<hr /><p><b>Generations:</b> Identifies the particular use or manner in which an instantiation of a media item is used. See also explanations of generation terms.</p>

<p><b>PBCore</b><br />
1.3 — formatGenerations<br />
2.0 — instantiationGenerations</p>

<p><b>Examples </b>(picklist)</p>

<p><b>•</b> Master: broadcast</p>

<p><b>•</b> Original recording</p>

<p><b>•</b> Radio program (Dub)</p>

<p><b>•</b> Reversal: internegative</p>

<hr /><p><b>Duration:</b> Provides a timestamp for the overall length or duration of a time-based media item. It represents the playback time. NOTE— In many instances you may not know the ACTUAL recorded time of the item you are inventorying. If this is the case, please check YES in the column to the right marked “Approximate?” This will help us differentiate from actual vs. estimated durations.</p>

<p><b>PBCore</b><br />
1.2 — formatDuration<br />
2.0 — instantiationDuration</p>

<p><b>Examples</b></p>

<p><b>•</b> 01:23:45:09 (SMPTE Timecode nondrop frame-NTSC)</p>

<p><b>•</b> 01;23;45;09 (SMPTE Timecode dropframe-NTSC)</p>

<p><b>•</b> 01:23:45.365 (Milliseconds Timecode)</p>

<p><b>•</b> 02:34:35 (HH:MM:SS)</p>

<hr /><p><b>Location: </b>May contain information about an organization or building, a specific vault location for an asset, including an organization’s name, departmental name, shelf ID and contact information. For a data file or web page, this location may be virtual and include domain, path, file name or html page. The data may be a name (person or organization),URL, URI, physical location ID, barcode, etc.</p>

<p><b>PBCore</b><br />
1.3 — formatLocation<br />
2.0 — instantiationLocation</p>

<p><b>Examples</b> (free form text entry)</p>

<p><b>•</b> Lindemann/Phillips 66 Oil Industry Archives of Bartlesville,OK</p>

<p><b>•</b> Eccles Broadcast Center; Vault Room 217; Section C: Shelf 5</p>

<p><b>•</b> Utah Education Network Licensed PBS Media DAM System</p>

<p><b>•</b> http://www.uen.org/PBS</p>

<p><b>•</b> J. Willard Marriott Library: Special Collections</p>

<hr /><p><b>Title: </b>The descriptor title is a name given to the media item you are cataloging.</p>

<p><b>PBCore</b><br />
1.3 — title<br />
2.0 — pbcoreTitle</p>

<p><b>Examples</b> (free form text entry)</p>

<p><b>•</b> Geography of Utah, The</p>

<p><b>•</b> American Experience</p>

<p><b>•</b> Day in the Life of Alan Smithee, A</p>

<p><b>•</b> Delicate Arch Olympic Flame Ceremony</p>

<hr /><p><b>Title Type:</b> a companion metadata field associated with the descriptor title. For a title you give to a media item, this allows you to inform end users what type of title it is.</p>

<p><b>PBCore</b><br />
1.3 — titleType<br />
2.0 — titleType (attribute)</p>

<p><b>Examples</b> (picklist)</p>

<p><b>•</b> Series</p>

<p><b>•</b> Program</p>

<p><b>•</b> Episode</p>

<p><b>•</b> Story</p>

<hr /><p><b>Asset Type:</b> Indicates the broad editorial format of the assets contents. AssetType describes the PBCore record as a whole and at its highest level. Though a record may contain many instantiations of different formats and generations, for example, assetType may be used to indicate that they all represent a “program” or a “clip.”</p>

<p><b>PBCore</b><br />
1.3 — pbcoreAssetType<br />
2.0 — pbcoreAssetType</p>

<p><b>Examples</b> (picklist)</p>

<p><b>•</b> Episode</p>

<p><b>•</b> Story</p>

<p><b>•</b> Shot</p>

<hr /><p><b>Identifier: </b>Used to reference or identify the entire record of metadata descriptions for a media item. In contrast to the Unique Identifier, this identifier is used to identify the CONTENT of the asset. So it links together all copies of a particular episode of NOVA or This American Life by assigning them all the same code.</p>

<p><b>PBCore</b><br />
1.3 — identifier<br />
2.0 — pbcoreIdentifier</p>

<p><b>Examples</b> (free form text entry)</p>

<p><b>•</b> NOVA003406</p>

<p><b>•</b> PROG_SV2BT0GDPUE28XX</p>

<hr /><p><b>Identifier Source: </b>Used in combination with the identifier for a media item. Provides the name of the agency or institution who assigned it, or system used.</p>

<p><b>PBCore</b><br />
1.3 — identifierSource<br />
2.0 — source (attribute)</p>

<p><b>Examples </b>(free form text entry)</p>

<p><b>•</b> NOLA Code</p>

<p><b>•</b> WGBH Mars</p>

<p><b>•</b> PRI Story No.</p>

<hr /><p><b>Description:</b> Uses free-form text or a narrative to report general notes, abstracts, or summaries about the intellectual content of a media item. May also consist of outlines, lists, bullet points, rundowns, edit decision lists, indexes, or tables of content.</p>

<p><b>PBCore</b><br />
1.3 — description<br />
2.0 — pbcoreDescription</p>

<p><b>Example</b> (free form text entry)<br />
On May 13, 1607, three English sailing vessels drop anchor beside a small island fringed by swamps in the James River, Virginia. On board are 104 colonists who will establish the first successful English settlement in the New World at Jamestown. The exploits of the brash, swashbuckling John Smith, the wily, venerable chief Powhatan, and his infatuated daughter Pocahontas will be recited, retold, and embroidered until they gather the status of an epic founding myth of the new nation. […]</p>

<hr /><p><b>Description Type:</b> A companion metadata field to the description. The purpose of descriptionType is to identify the nature of the actual description and flag the form of presentation for the information.</p>

<p><b>PBCore</b><br />
1.3 — descriptionType<br />
2.0 — descriptionType (attribute)</p>

<p><b>Examples</b> (picklist)</p>

<p><b>•</b> Review</p>

<p><b>•</b> Shot List</p>

<p><b>•</b> Summary</p>

<hr /><p><b>Subject:</b> Used to assign topical headings or keywords that portray the intellectual content of the media item. Controlled vocabularies, authorities, or formal classification schemes may be employed when assigning descriptive subject terms (rather than using random or ad hoc terminology).</p>

<p><b>PBCore</b><br />
1.3 — subject<br />
2.0 — pbcoreSubject</p>

<p><b>Examples:</b></p>

<p><b>•</b> Smith, John, 1580–1631</p>

<p><b>•</b> Jamestown (Va.)</p>

<p><b>•</b> Music–20th century</p>

<hr /><p><b>Subject Authority Used:</b> If subjects are assigned to a media item using the descriptor subject and the terms used are derived from a specific authority or classification scheme, use this field to identify whose vocabularies and terms were used.</p>

<p><b>PBCore</b><br />
1.3 — subjectAuthorityUsed<br />
2.0 — source (attribute)</p>

<p><b>Examples:</b></p>

<p><b>•</b> Library of Congress Name Authority</p>

<p><b>•</b> Library of Congress Subject Headings</p>

<p><b>•</b> COVE Topics</p>

<hr /><p><b>Genre: </b>Describes the manner in which the intellectual content of a media item is presented, viewed or heard by a user. It indicates the structure of the presentation, as well as the topical nature of the content in a generalized form.</p>

<p><b>PBCore</b><br />
1.3 — genre<br />
2.0 — pbcoreGenre</p>

<p><b>Examples</b> (picklist)</p>

<p><b>•</b> Art</p>

<p><b>•</b> History</p>

<p><b>•</b> News</p>

<hr /><p><b>Genre Authority Used:</b> If genre keywords are assigned to a media item using the descriptor genre and the terms used are derived from a specific authority or classification scheme, use genreAuthorityUsed to identify whose vocabularies and terms were used. PBcore supplies its own picklist of terms, but others may be employed as long as the authority for a picklist is identified. (If selecting from the drop down in “genre” — you are using the PBCore pbcoreGenre authority).</p>

<p><b>PBCore</b><br />
1.3 — genreAuthorityUsed<br />
2.0 — source (attribute)</p>

<p><b>Examples</b></p>

<p><b>•</b> Public Broadcasting Service /PODS Program Offer Data Service Metadata Dictionary</p>

<p><b>•</b> PBCoreGenre</p>

<p><b>•</b> State of Utah. Film Commission</p>

<hr /><p><b>Coverage:</b> Uses keywords to identify a span of space or time that is expressed by the intellectual content of a media item. Coverage in intellectual content may be expressed spatially by geographic location. Actual place names may be used. Numeric coordinates and geo-spatial data are also allowable, if useful or supplied. Coverage in intellectual content may also be expressed temporally by a date, period, era, or time-based event. The PBCore metadata element coverage houses the actual spatial or temporal keywords. The companion descriptor coverageType is used to identify the type of keywords that are being used.</p>

<p><b>PBCore</b><br />
1.3 — coverage<br />
2.0 — coverage</p>

<p><b>Examples</b></p>

<p><b>•</b> Washington, DC</p>

<p><b>•</b> 37.2000,-76.7667</p>

<p><b>•</b> Great Depression</p>

<p><b>•</b> 1776–1789</p>

<p><b>•</b> November 22, 1963</p>

<hr /><p><b>Coverage Type:</b> Used to identify the actual type of keywords that are being used by its companion metadata element coverage. coverageType provides a picklist of types, namely spatial or temporal, because coverage in intellectual content may be expressed spatially by geographic location or it may also be expressed temporally by a date, period, era, or time-based event.</p>

<p><b>PBCore</b><br />
1.3 — coverageType<br />
2.0 — coverageType</p>

<p><b>Examples</b> (picklist)</p>

<p><b>•</b> spatial</p>

<p><b>•</b> temporal</p>

<hr /><p><b>Language: </b>Identifies the primary language of a media item’s audio or text. Best practice is to use the 3 letter ISO <a href="http://www.loc.gov/standards/iso639-2/php/code_list.php">639.2</a> or <a href="http://www.sil.org/iso639-3/codes.asp">639.3</a> code for languages. If the media item has more than one language that is considered part of the same primary audio or text, then a combination statement can be crafted, e.g., eng;fre for the presence of both English and French in the primary audio. Separating three-letter language codes with a semi-colon (no additional spaces) is preferred.</p>

<p><b>PBCore</b><br />
1.3 — language<br />
2.0 — instantiationLanguage</p>

<p><b>Examples</b></p>

<p><b>•</b> eng</p>

<p><b>•</b> fre</p>

<p><b>•</b> spa</p>

<hr /><p><b>Date Created: </b>Specifies the creation date for a particular version or rendition of a media item across its life cycle. It is the moment in time that the media item was finalized during its production process and is forwarded to other divisions or agencies to make it ready for publication or distribution. The recommended format consists of a text string for the representation of dates YYYY-MM-DD (1998–01-24). If you don’t have a full YYYY-MM-DD then use this format to the extent of the information you do have.</p>

<p><b>PBCore</b><br />
1.3 — dateCreated<br />
2.0 — instantiationDate, dateType=created</p>

<p><b>Examples</b></p>

<p><b>•</b> 1997-07-16</p>

<p><b>•</b> 2007-04</p>

<p><b>•</b> 1987</p>

<hr /><p><b>Date Broadcast/Issued: </b>Specifies the formal date for a particular version or rendition of a media item has been made ready or officially released for distribution, publication or consumption. The recommended format consists of a text string for the representation of dates YYYY-MM-DD (1998–01-24). If you don’t have a full YYYY-MM-DD then use this format to the extent of the information you do have.</p>

<p><b>PBCore</b><br />
1.3 — dateIssued<br />
2.0 — instantiationDate, dateType=issued</p>

<p><b>Examples</b></p>

<p><b>•</b> 1997-07-16</p>

<p><b>•</b> 2007-04</p>

<p><b>•</b> 1987</p>

<hr /><p><b>Creator: </b>Identifies a person or organization primarily responsible for creating a media item. The creator may be considered an author and could be one or more people, a business, organization, group, project or service.</p>

<p><b>PBCore</b><br />
1.3 — creator<br />
2.0 — creator</p>

<p><b>Examples</b> (free form text entry)</p>

<p><b>•</b> WGBH Educational Foundation</p>

<p><b>•</b> Ken Burns</p>

<hr /><p><b>Creator Role:</b> Identifies the role played by the person or group identified in the companion descriptor Creator.</p>

<p><b>PBCore</b><br />
1.3 — creatorRole<br />
2.0 — creatorRole</p>

<p><b>Examples</b> (picklist)</p>

<p><b>•</b> Producer</p>

<p><b>•</b> Editor</p>

<p><b>•</b> Writer</p>

<hr /><p><b>Contributor: </b>Identifies a person or organization that has made substantial creative contributions to the intellectual content within a media item. This contribution is considered to be secondary to the primary author(s) (person or organization) identified in the descriptor Creator.</p>

<p><b>PBCore</b><br />
1.3 — contributor<br />
2.0 — contributor</p>

<p><b>Examples </b>(free form text entry)</p>

<p><b>•</b> Lisa Quijano Wolfinger</p>

<p><b>•</b> Yo-Yo Ma</p>

<hr /><p><b>Contributor Role:</b> Identifies the role played by the person or group identified in the companion descriptor Contributor.</p>

<p><b>PBCore</b><br />
1.3 — contributorRole<br />
2.0 — contributorRole</p>

<p><b>Examples</b> (picklist)</p>

<p><b>•</b> Narrator</p>

<p><b>•</b> Instrumentalist</p>

<hr /><p><b>Publisher: </b>Identifies a person or organization primarily responsible for distributing or making a media item available to others. The publisher may be a person, a business, organization, group, project or service.</p>

<p><b>PBCore</b><br />
1.3 — publisher<br />
2.0 — publisher</p>

<p><b>Examples</b> (free form text entry)</p>

<p><b>•</b> WNET.org</p>

<p><b>•</b> Public Broadcasting Service</p>

<hr /><p><b>Publisher Role:</b> Identifies the role played by the specific publisher or publishing entity identified in the companion descriptor Publisher.</p>

<p><b>PBCore</b><br />
1.3 — publisherRole<br />
2.0 — publisherRole</p>

<p><b>Examples </b>(picklist)</p>

<p><b>•</b> Copyright Holder</p>

<p><b>•</b> Distributor</p>

<hr /><p><b>Rights Summary: </b>An all-purpose container field to identify information about copyrights and property rights held in and over a media item, whether they are open access or restricted in some way. If dates, times and availability periods are associated with a right, include them. End user permissions, constraints and obligations may also be identified, as needed.</p>

<p><b>PBCore</b><br />
1.3 — rightsSummary<br />
2.0 — rightsSummary</p>

<p><b>Examples</b> (free form text entry)</p>

<p><b>•</b> c. 1998 WNET.org</p>

<p><b>•</b> Download and Share</p>

<p><b>•</b> CC 3.0</p>

<hr/>
	  
  </div>
  <div class="tab-pane" id="minthelp">
	  

<p>Mapping Records into the American Archive Archival Management System using the MINT Mapping Tool</p>

<p>This tutorial is to give all future content contributors to the American Archive an overview of how to use some of the advanced features of our Archival Management System or AMS, specifically the mapping tool which allows contributors to take existing catalog records and map them into the AMS so that they become part of the American Archive.</p>

<p>The ability to use the mapping tool is not going to appear to all users automatically. Use will be cleared by the American Archive staff on a case by case basis once a user has demonstrated that they have an understanding of how to use the tool. Once the staff has determined this, they can upgrade your permissions level so that you can use the tool. </p>

<p>The mapping tool is based on software called MINT which stands for Metadata Interoperability Services. There is a user manual online for the MINT tool which can be accessed here:</p>

<p><a href="http://mint.image.ece.ntua.gr/redmine/projects/mint/wiki/User_manual">http://mint.image.ece.ntua.gr/redmine/projects/mint/wiki/User_manual</a></p>

<p>MINT supports several different formats for mapping and ingest, including the two most common formats - XML and Comma Separated Values (or CSV). For this tutorial we are going to cover the most common format – the CSV file.</p>

<p>I’m going to construct this tutorial in three parts –1) prepping your CSV column headings to align with PBCore, 2) formatting your CSV, and 3) importing the CSV to MINT and assigning mapping.</p>

<p>I. Prepping CSV column headings</p>

<p>Online Screencast Tutorial: <a href="https://cpbnet.webex.com/cpbnet/ldr.php?AT=pb&amp;SP=MC&amp;rID=14453287&amp;rKey=5dc01b8cc686f77f">https://cpbnet.webex.com/cpbnet/ldr.php?AT=pb&amp;SP=MC&amp;rID=14453287&amp;rKey=5dc01b8cc686f77f</a> </p>

<p>For this step, you will need to have an understanding of the metadata schema that the Archive uses within the AMS – PBCore 2.0. If you aren’t already familiar with this schema, please take some time to visit the PBCore website and familiarize yourself with the various fields, or ‘elements’ as we call them. You will need to conform your CSV column headings to these elements prior to importing your CSV document and mapping your records:</p>

<p><a href="http://www.pbcore.org/elements/">http://www.pbcore.org/elements/</a></p>

<p>Many of you will have been using Microsoft Excel to work with your existing records, or will have exported data from another system into Excel or CSV form. Open up your document and create a new row underneath the current header row where you will track how your current columns will map to PBCore elements. Please note that eventually you will need to delete all Excel formatting and delete the old header row prior to saving the final CSV file.</p>

<p>In this example you can see that the data that came in from University of Maryland had header rows labeled ‘Series Title’ and ‘Program Title.’ But if you look at the PBCore schema, you’ll see that the way this is represented in PBCore is to have a Title and a Title Type, both of which are repeatable. So you’ll want to create two new columns to the right of each Title column, and relabel the columns to align with PBCore elements. So the column ‘Series Title’ should be transformed to two columns and relabeled to read ‘pbcoreTitle’ and ‘pbcoreTitle titleType,’ with the entire titleType column reading ‘Series.’ For the ‘Program Title’ column, the two new/relabeled columns should read ‘pbcoreTitle2’ and ‘pbcoreTitle titleType2,’ with the entire titleType2 column reading ‘Program.’ </p>

<p>The same rules should apply to other elements such as Unique ID, which should also have a Unique ID Source defined. In this example, there are TWO Unique IDs with two distinct sources. In this case, one ID was assigned by the University of Maryland and another ID was originally assigned by the National Association of Educational Broadcasters. Similarly to the above Title example, you will relabel the ID columns to ‘pbcoreIdentifier’ and ‘pbcoreIdentifier2,’ then create two new columns to the right of each ID column, labeling them ‘pbcoreIdentifier source’ and ‘pbcoreIdentifier source2.’</p>

<p>Here is another more tricky example of the PBCore mapping preparation. In one instance for the University of Maryland records, one column contained information about what PBCore would called ‘pbcoreCreator’ metadata. The column contained some records with a Reporter’s name, and other records with the name of the Producer. In this case, you would relabel this column ‘pbcoreCreator,’ and you would still have to create a new column to the right of that column and label it ‘pbcoreCreator role.’ But instead of pasting in one Creator Role term to all records, you would assign the value ‘Reporter’ to some records and ‘Producer’ to others. In this example, many records in this column actually had no value assigned.</p>

<p>Here is one final mapping preparation example in the category of ‘Extra Tricky.’ University of Maryland’s original data contained a column labeled ‘Format,’ with terms such as ‘5" RL-7 1/2 IPS’ in it. What this short hand actually translates to is that these are 1/4" audio tapes on 5” reels which run at a speed of 7 ½ inches per second (or ips). These values need to be split among several new columns. First, you would create columns for the unit’s Dimensions (in this case, 5 inches), and the Frame Rate as well as the units of measure FOR the frame rate (in this case, ‘7 ½’ and ‘ips,’ respectively). Once you have parsed that data out, you can relabel the ‘Format’ column with instantiationPhysical, and replace the values in that column with ‘1/4" audio tape.’</p>

<p>You may want to contact the staff of the American Archive to review your final CSV preparation before trying to import the CSV file, just to ensure that they were transformed correctly. After this step has been accomplished, you will need to reformat your CSV document so that all Excel formatting is stripped out. This step is detailed in Part 2 of this tutorial.</p>

<p>II. Formatting your CSV</p>

<p>Online Screencast Tutorial:</p>

<p><a href="https://cpbnet.webex.com/cpbnet/ldr.php?AT=pb&amp;SP=MC&amp;rID=14453297&amp;rKey=b7e262787bada36f">https://cpbnet.webex.com/cpbnet/ldr.php?AT=pb&amp;SP=MC&amp;rID=14453297&amp;rKey=b7e262787bada36f</a> </p>

<p>At this point you’ll want to save your work and then prepare to save a new version of your CSV document that strips out all of the automatic formatting that occurs when you use Excel. The most important two fields that need careful attention during this step are columns that contain numbers, most importantly Duration (expressed in time code) and Dates.</p>

<p>In the Univeristy of Maryland example, you can see here that when I click on one of the values in the Date column, Excel has formatted it AS a Date. Unfortunately you cannot simply reformat this directly to Text format, or else it transforms the date into some unknown integer that cannot be turned back into a date format.</p>

<p>There are likely a few ways to do this, but I’m going to show you a workaround. First, create a new column to the right of your Date (or Duration) column. Select the entire column and format it as Text. Next, select the entire column containing your dates, and copy that entire column to the clipboard. Then open a text editor, either Notepad on the PC or TextEdit on the Mac, and paste that data into it. Once the data has been pasted it, it is now officially stripped of formatting. Select all, and copy the stripped data back onto the clipboard. Back in the CSV document, paste the textual data into the text-formatted column. As you can see, the data in the two columns is identical, but the data in the new column is formatted as text.</p>

<p>The same principal can be applied to the Duration column, which may be formatted in Excel as a Time type. You can walk through the same steps outlined above to transform this data into text.</p>

<p>After you have clicked through each column to ensure that they are all formatted as text, it is good practice to take precautions and select all the records in the document and format them all as text before saving. Then click on ‘Save As’ and select ‘CSV’ from the drop down menu.</p>

<p>III. Using MINT to import and map your CSV records</p>

<p>Online Screencast Tutorial:</p>

<p><a href="https://cpbnet.webex.com/cpbnet/ldr.php?AT=pb&amp;SP=MC&amp;rID=14480192&amp;rKey=23a71c954685ab05">https://cpbnet.webex.com/cpbnet/ldr.php?AT=pb&amp;SP=MC&amp;rID=14480192&amp;rKey=23a71c954685ab05</a> </p>

<p>In this tutorial we’re going to talk about mapping your data into the MINT tool. To perform these steps, you must start by logging into the AMS and clicking on the ‘Records’ tab. Click on the Operations pulldown list, and select your organization from the list (which should in most cases only display one option). </p>

<p>This should direct you to MINT interface, in the ‘Import’ tab. Check ‘This is a CSV Upload’ and in the ‘Define the Escape Character box,’ select the backslash. Click on Choose File, select the pre-formatted CSV with your mapped column headings, and click Submit.</p>

<p>Now you will see the screen titled ‘Overview’ with a list of Imports that you have uploaded. The CSV you just imported will take a few moments to upload. Once it is completed, click on the file name of the uploaded document. You should see three icons – (left to right) Root, Mapping, and Transform. Click on Mapping to launch the mapping editor. </p>

<p>Select ‘Add New Mapping’ and name the mapping you are creating. In the ‘Create New Schema’ box, leave the select as ‘AMS PBCore.’</p>

<p>Now you should see the mapping editor interface, with three sections. One the left should be ‘Source Schema,’ in the center should be ‘Mappings,’ and on the right should be ‘Target Schema.’ </p>

<p>On the Source Schema section, click on the plus signs to expand the structure of your imported CSV document. Now you should see each of your column headings displayed on the left.</p>

<p>On the Target Schema section, click on ‘pbcoreDescriptionDocument,’ which should display all of the PBCore elements in the center Mappings section.</p>

<p>At this point, the task is to pull each of your Source Schema elements into the appropriate PBCore element in the Mappings section. Please note the following:</p>

<p>- Most elements have an attribute (i.e. a title has a title type, a unique ID has a source, a creator has a creator role) associated with them. For most of these, to access the attribute, click on the @ sign to expand the element and display the attribute. However, for certain elements such as Creator and others, the Creator Role attribute is actually its own element within a ‘structural’ container. This is why the Creator element in the Mapping section does not have a place to drag the source schema element. To access both of those elements (Creator and Creator Source), click on the plus sign on the far left to expand the Creator structural element.</p>

<p>- Similarly, please note that all elements related to the physical object, or instantiation, are elements within the structural container for Instantiation (pbcoreInstantiation). So if you don’t immediately see these fields (i.e. physical format, duration, etc), click on the plus sign on the far left next to pbcoreInstantiation, which will display all of those elements.</p>

<p>- You can hard code any value into all records at once. So for example, if you forgot to create a column in your CSV for Title Type, but know all titles are a ‘Series’ title type, you can hard code ‘Series’ into the entire data set. So after you map the Title source element to Title, click on the @ sign and then double click on the ‘unmapped’ text. You will get a dialogue box prompt where you can manually type a value. In this case, you would type ‘Series’ and click OK.</p>

<p>- If there are repeating fields of the same element (i.e. two titles, or two unique IDs), you will need to duplicate that element in the Mappings section by clicking on the small green plus sign. This will create a duplicate at the BOTTOM of the page.</p>

<p>- Mostly when you drag one source element into a target element, it turns blue on the left to inform you that it has been successfully mapped. HOWEVER, when there are repeating fields of the same element (i.e. two titles, or two unique IDs), the second element will NOT turn blue, so you will have to keep track of the fact that you successfully mapped that second repeating element.</p>

<p>Once you have mapped each source element into the Mapping section, click on Preview at the top of the screen to see how your data looks and whether it is fully valid. Click on the Validation tab to see if there were any issues with how your data was mapped. It is entirely possible that one or two things will prevent your mapping from being 100% valid, but usually they are small fixes that can be easily remedied. Even in this mapping demonstration/screencast, there were two elements that needed correction before this was considered a valid mapping. This need not prevent you from saving your mapping – do this by clicking Finished at the top of the screen. Contact the American Archive staff to help you make any final fixes and ensure that your mapping is valid.</p>

<p>Once the Validation tab reads Valid, and you’ve selected Finished to save your work, return to the Overview screen and click again on the name of the CSV file that you just mapped. Click on the ‘Transform’ button to commit this mapping to the AMS. Please note that the American Archive staff must approve your transformed and mapped data before it can be fully integrated into the AMS.</p>

	  
  </div>
  
</div>



