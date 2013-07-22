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
