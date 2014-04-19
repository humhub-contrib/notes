<?php

/**
 * NoteUpdatedNotification is fired to all authors of a pad, if an user made changes.
 *
 * @author Andreas Strobel
 */
class NoteUpdatedNotification extends Notification {

    // Path to Web View of this Notification
    public $webView = "notes.views.notifications.NoteUpdated";
    // Path to Mail Template for this notification
    public $mailView = "application.modules.notes.views.notifications.NoteUpdated_mail";

}

?>
