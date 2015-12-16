"use strict";

module.exports = function(shariff) {
	var url = encodeURIComponent(shariff.getURL());
	
	var title = shariff.getMeta("DC.title");
	var creator = shariff.getMeta("DC.creator");
	
	if (title.length > 0 && creator.length > 0) {
		title += " - " + creator;
	} 
	else {
		title = shariff.getTitle();
	}
	
	return {
		"popup": false,
		"mobileonly": false,
		"blank": false,
		"shareText": {
			"bg": "имейл",
			"da": "e-mail",
			"de": "e-mail",
			"en": "e-mail",
			"es": "emilio",
			"fi": "sähköpostitse",
			"fr": "courriel",
			"hr": "e-pošta",
			"hu": "e-mail",
			"it": "e-mail",
			"ja": "e-mail",
			"ko": "e-mail",
			"nl": "e-mail",
			"no": "e-post",
			"pl": "e-mail",
			"pt": "e-mail",
			"ro": "e-mail",
			"ru": "e-mail",
			"sk": "e-mail",
			"sl": "e-mail",
			"sr": "e-mail",
			"sv": "e-post",
			"tr": "e-posta",
			"zh": "e-mail"
		},
		"name": "mailto",
		"faName": "s3uu-envelope",
		"title": {
			"bg": "Изпрати по имейл",
			"da": "Sende via e-mail",
			"de": "Per E-Mail versenden",
			"en": "Send by email",
			"es": "Enviar por email",
			"fi": "Lähetä sähköpostitse",
			"fr": "Envoyer par courriel",
			"hr": "Pošaljite emailom",
			"hu": "Elküldés e-mailben",
			"it": "Inviare via e-mail",
			"ja": "電子メールで送信",
			"ko": "이메일로 보내기",
			"nl": "Sturen via e-mail",
			"no": "Send via epost",
			"pl": "Wyślij e-mailem",
			"pt": "Enviar por e-mail",
			"ro": "Trimite prin e-mail",
			"ru": "Отправить по эл. почте",
			"sk": "Poslať e-mailom",
			"sl": "Pošlji po elektronski pošti",
			"sr": "Pošalji putem email-a",
			"sv": "Skicka via e-post",
			"tr": "E-posta ile gönder",
			"zh": "通过电子邮件传送"
		},
		"shareUrl": "mailto:?body=" + url + shariff.getReferrerTrack() + "&subject=" + encodeURIComponent(title)		
	};
};
