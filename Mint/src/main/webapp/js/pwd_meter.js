/*
**    Original File: pwd_meter.js
**    Created by: Jeff Todnem (http://www.todnem.com/)
**    Created on: 2007-08-14
**    Last modified: 2007-08-30
**
**    License Information:
**    -------------------------------------------------------------------------
**    Copyright (C) 2007 Jeff Todnem
**
**    This program is free software; you can redistribute it and/or modify it
**    under the terms of the GNU General Public License as published by the
**    Free Software Foundation; either version 2 of the License, or (at your
**    option) any later version.
**    
**    This program is distributed in the hope that it will be useful, but
**    WITHOUT ANY WARRANTY; without even the implied warranty of
**    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
**    General Public License for more details.
**    
**    You should have received a copy of the GNU General Public License along
**    with this program; if not, write to the Free Software Foundation, Inc.,
**    59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
**    
**    
*/

function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != "function") {
		window.onload = func;
	}
	else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		};
	}
}

String.prototype.strReverse = function() {
	var newstring = "";
	for (var s=0; s < this.length; s++) {
		newstring = this.charAt(s) + newstring;
	}
	return newstring;
	//strOrig = ' texttotrim ';
	//strReversed = strOrig.revstring();
};

function chkPass(pwd) {
	var oScorebar = document.getElementById("scorebar");
	var oScore = document.getElementById("score");
	var oComplexity = document.getElementById("complexity");
	var nScore = 0;
	var nLength = 0;
	var nAlphaUC = 0;
	var nAlphaLC = 0;
	var nNumber = 0;
	var nSymbol = 0;
	var nMidChar = 0;
	var nRequirements = 0;
	var nAlphasOnly = 0;
	var nNumbersOnly = 0;
	var nRepChar = 0;
	var nConsecAlphaUC = 0;
	var nConsecAlphaLC = 0;
	var nConsecNumber = 0;
	var nConsecSymbol = 0;
	var nConsecCharType = 0;
	var nSeqAlpha = 0;
	var nSeqNumber = 0;
	var nSeqChar = 0;
	var nReqChar = 0;
	var nReqCharType = 3;
	var nMultLength = 4;
	var nMultAlphaUC = 3;
	var nMultAlphaLC = 3;
	var nMultNumber = 4;
	var nMultSymbol = 6;
	var nMultMidChar = 2;
	var nMultRequirements = 2;
	var nMultRepChar = 1;
	var nMultConsecAlphaUC = 2;
	var nMultConsecAlphaLC = 2;
	var nMultConsecNumber = 2;
	var nMultConsecSymbol = 1;
	var nMultConsecCharType = 0;
	var nMultSeqAlpha = 3;
	var nMultSeqNumber = 3;
	var nTmpAlphaUC = "";
	var nTmpAlphaLC = "";
	var nTmpNumber = "";
	var nTmpSymbol = "";
	var sAlphaUC = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sAlphaLC = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sNumber = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sSymbol = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sMidChar = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sRequirements = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sAlphasOnly = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sNumbersOnly = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sRepChar = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sConsecAlphaUC = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sConsecAlphaLC = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sConsecNumber = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sSeqAlpha = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sSeqNumber = "&nbsp;&nbsp;&nbsp;&nbsp;0";
	var sAlphas = "abcdefghijklmnopqrstuvwxyz";
	var sNumerics = "01234567890";
	var sComplexity = "Too Short";
	var sStandards = "Below";
	var nMinPwdLen = 8;
	if (document.all) { var nd = 0; } else { var nd = 1; }
	if (pwd) {
		nScore = parseInt(pwd.length * nMultLength);
		nLength = pwd.length;
		var arrPwd = pwd.replace (/\s+/g,"").split(/\s*/);
		var arrPwdLen = arrPwd.length;
		
		/* Loop through password to check for Symbol, Numeric, Lowercase and Uppercase pattern matches */
		for (var a=0; a < arrPwdLen; a++) {
			if (arrPwd[a].match(new RegExp(/[A-Z]/g))) {
				if (nTmpAlphaUC !== "") { if ((nTmpAlphaUC + 1) == a) { nConsecAlphaUC++; nConsecCharType++; } }
				nTmpAlphaUC = a;
				nAlphaUC++;
			}
			else if (arrPwd[a].match(new RegExp(/[a-z]/g))) { 
				if (nTmpAlphaLC !== "") { if ((nTmpAlphaLC + 1) == a) { nConsecAlphaLC++; nConsecCharType++; } }
				nTmpAlphaLC = a;
				nAlphaLC++;
			}
			else if (arrPwd[a].match(new RegExp(/[0-9]/g))) { 
				if (a > 0 && a < (arrPwdLen - 1)) { nMidChar++; }
				if (nTmpNumber !== "") { if ((nTmpNumber + 1) == a) { nConsecNumber++; nConsecCharType++; } }
				nTmpNumber = a;
				nNumber++;
			}
			else if (arrPwd[a].match(new RegExp(/[^a-zA-Z0-9_]/g))) { 
				if (a > 0 && a < (arrPwdLen - 1)) { nMidChar++; }
				if (nTmpSymbol !== "") { if ((nTmpSymbol + 1) == a) { nConsecSymbol++; nConsecCharType++; } }
				nTmpSymbol = a;
				nSymbol++;
			}
			/* Internal loop through password to check for repeated characters */
			for (var b=0; b < arrPwdLen; b++) {
				if (arrPwd[a].toLowerCase() == arrPwd[b].toLowerCase() && a != b) { nRepChar++; }
			}
		}
		
		/* Check for sequential alpha string patterns (forward and reverse) */
		for (var s=0; s < 23; s++) {
			var sFwd = sAlphas.substring(s,parseInt(s+3));
			var sRev = sFwd.strReverse();
			if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) { nSeqAlpha++; nSeqChar++;}
		}
		
		/* Check for sequential numeric string patterns (forward and reverse) */
		for (var s=0; s < 8; s++) {
			var sFwd = sNumerics.substring(s,parseInt(s+3));
			var sRev = sFwd.strReverse();
			if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) { nSeqNumber++; nSeqChar++;}
		}
		
	/* Modify overall score value based on usage vs requirements */

		/* General point assignment */
		if (nAlphaUC > 0 && nAlphaUC < nLength) {	
			nScore = parseInt(nScore + ((nLength - nAlphaUC) * 2));
			sAlphaUC = "+ " + parseInt((nLength - nAlphaUC) * 2); 
		}
		if (nAlphaLC > 0 && nAlphaLC < nLength) {	
			nScore = parseInt(nScore + ((nLength - nAlphaLC) * 2)); 
			sAlphaLC = "+ " + parseInt((nLength - nAlphaLC) * 2);
		}
		if (nNumber > 0 && nNumber < nLength) {	
			nScore = parseInt(nScore + (nNumber * nMultNumber));
			sNumber = "+ " + parseInt(nNumber * nMultNumber);
		}
		if (nSymbol > 0) {	
			nScore = parseInt(nScore + (nSymbol * nMultSymbol));
			sSymbol = "+ " + parseInt(nSymbol * nMultSymbol);
		}
		if (nMidChar > 0) {	
			nScore = parseInt(nScore + (nMidChar * nMultMidChar));
			sMidChar = "+ " + parseInt(nMidChar * nMultMidChar);
		}

		/* Point deductions for poor practices */
		if ((nAlphaLC > 0 || nAlphaUC > 0) && nSymbol === 0 && nNumber === 0) {  // Only Letters
			nScore = parseInt(nScore - nLength);
			nAlphasOnly = nLength;
			sAlphasOnly = "- " + nLength;
		}
		if (nAlphaLC === 0 && nAlphaUC === 0 && nSymbol === 0 && nNumber > 0) {  // Only Numbers
			nScore = parseInt(nScore - nLength); 
			nNumbersOnly = nLength;
			sNumbersOnly = "- " + nLength;
		}
		if (nRepChar > 0) {  // Same character exists more than once
			nScore = parseInt(nScore - (nRepChar * nRepChar));
			sRepChar = "- " + nRepChar;
		}
		if (nConsecAlphaUC > 0) {  // Consecutive Uppercase Letters exist
			nScore = parseInt(nScore - (nConsecAlphaUC * nMultConsecAlphaUC)); 
			sConsecAlphaUC = "- " + parseInt(nConsecAlphaUC * nMultConsecAlphaUC);
		}
		if (nConsecAlphaLC > 0) {  // Consecutive Lowercase Letters exist
			nScore = parseInt(nScore - (nConsecAlphaLC * nMultConsecAlphaLC)); 
			sConsecAlphaLC = "- " + parseInt(nConsecAlphaLC * nMultConsecAlphaLC);
		}
		if (nConsecNumber > 0) {  // Consecutive Numbers exist
			nScore = parseInt(nScore - (nConsecNumber * nMultConsecNumber));  
			sConsecNumber = "- " + parseInt(nConsecNumber * nMultConsecNumber);
		}
		if (nSeqAlpha > 0) {  // Sequential alpha strings exist (3 characters or more)
			nScore = parseInt(nScore - (nSeqAlpha * nMultSeqAlpha)); 
			sSeqAlpha = "- " + parseInt(nSeqAlpha * nMultSeqAlpha);
		}
		if (nSeqNumber > 0) {  // Sequential numeric strings exist (3 characters or more)
			nScore = parseInt(nScore - (nSeqNumber * nMultSeqNumber)); 
			sSeqNumber = "- " + parseInt(nSeqNumber * nMultSeqNumber);
		}

		/* Determine if mandatory requirements have been met and set image indicators accordingly */
		var arrChars = [nLength,nAlphaUC,nAlphaLC,nNumber,nSymbol];
		var arrCharsIds = ["nLength","nAlphaUC","nAlphaLC","nNumber","nSymbol"];
		var arrCharsLen = arrChars.length;
		for (var c=0; c < arrCharsLen; c++) {
			if (arrCharsIds[c] == "nLength") { var minVal = parseInt(nMinPwdLen - 1); } else { var minVal = 0; }
			if (arrChars[c] == parseInt(minVal + 1)) { nReqChar++; }
			else if (arrChars[c] > parseInt(minVal + 1)) { nReqChar++; }
		}
		nRequirements = nReqChar;
		if (pwd.length >= nMinPwdLen) { var nMinReqChars = 3; } else { var nMinReqChars = 4; }
		if (nRequirements > nMinReqChars) {  // One or more required characters exist
			nScore = parseInt(nScore + (nRequirements * 2)); 
			sRequirements = "+ " + parseInt(nRequirements * 2);
		}

		/* Determine if additional bonuses need to be applied and set image indicators accordingly */
		var arrChars = [nMidChar,nRequirements];
		var arrCharsIds = ["nMidChar","nRequirements"];
		var arrCharsLen = arrChars.length;
		for (var c=0; c < arrCharsLen; c++) {
			if (arrCharsIds[c] == "nRequirements") { var minVal = nMinReqChars; } else { var minVal = 0; }
		}

		/* Determine if suggested requirements have been met and set image indicators accordingly */
		var arrChars = [nAlphasOnly,nNumbersOnly,nRepChar,nConsecAlphaUC,nConsecAlphaLC,nConsecNumber,nSeqAlpha,nSeqNumber];
		var arrCharsIds = ["nAlphasOnly","nNumbersOnly","nRepChar","nConsecAlphaUC","nConsecAlphaLC","nConsecNumber","nSeqAlpha","nSeqNumber"];
		var arrCharsLen = arrChars.length;
		
		/* Determine complexity based on overall score */
		if (nScore > 100) { nScore = 100; } else if (nScore < 0) { nScore = 0; }
		if (nScore >= 0 && nScore < 20) { sComplexity = "Very Weak"; }
		else if (nScore >= 20 && nScore < 40) { sComplexity = "Weak"; }
		else if (nScore >= 40 && nScore < 60) { sComplexity = "Good"; }
		else if (nScore >= 60 && nScore < 80) { sComplexity = "Strong"; }
		else if (nScore >= 80 && nScore <= 100) { sComplexity = "Very Strong"; }
		
		/* Display updated score criteria to client */
		var arrImages = [ "fail", "warn", "pass", "meet", "exceed", "exceed"];
		oScorebar.innerHTML = '<img src="images/img_status_' + arrImages[Math.floor(nScore / 20)] + '.png"/>&nbsp;' + sComplexity;
	}
	else {
		/* Display default score criteria to client */
		initPwdChk();
	}
}

function togPwdMask() {

}

function initPwdChk(restart) {
	/* Reset all form values to their default */
	document.getElementById("scorebar").style.backgroundPosition = "0";
}

addLoadEvent(function() { initPwdChk(1); });

