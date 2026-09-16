/**
 * ThreatIQ free Gmail mail relay (Google Apps Script)
 *
 * Setup (2 minutes, free):
 * 1) Open https://script.google.com with threatiqsy@gmail.com
 * 2) New project → paste this whole file
 * 3) Project Settings → Script properties → Add:
 *      SECRET = choose any long random string
 * 4) Deploy → New deployment → Type: Web app
 *      Execute as: Me
 *      Who has access: Anyone
 * 5) Copy the Web App URL and send it to the developer with the SECRET
 */

function doPost(e) {
  try {
    var data = JSON.parse(e.postData.contents || '{}');
    var expected = PropertiesService.getScriptProperties().getProperty('SECRET');

    if (!expected || data.secret !== expected) {
      return json_({ ok: false, error: 'unauthorized' });
    }

    if (!data.to || !data.subject || !data.body) {
      return json_({ ok: false, error: 'missing_fields' });
    }

    GmailApp.sendEmail(String(data.to), String(data.subject), String(data.body), {
      name: data.fromName || 'ThreatIQ',
      replyTo: data.replyTo || Session.getActiveUser().getEmail(),
    });

    return json_({ ok: true });
  } catch (err) {
    return json_({ ok: false, error: String(err) });
  }
}

function json_(obj) {
  return ContentService
    .createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}
