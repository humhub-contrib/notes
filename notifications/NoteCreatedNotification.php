<?php

/**
 * NoteCreatedNotification is fired to the user who manually add to a note for getting a notification.
 *
 * @author Andreas Strobel
 */
class NoteCreatedNotification extends Notification {

    // Path to Web View of this Notification
    public $webView = "notes.views.notifications.NoteCreated";
    // Path to Mail Template for this notification
    public $mailView = "application.modules.notes.views.notifications.NoteCreated_mail";

}

?>
