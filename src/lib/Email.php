<?php
/**
 * Email Notification System
 * Handles sending emails for order confirmations, password resets, etc.
 */

class Email {
    /**
     * Send order confirmation email
     */
    public static function sendOrderConfirmation($userEmail, $orderNumber, $orderDetails) {
        $subject = "Confirmation de commande #{$orderNumber} - Sultan Library";
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #56474A; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .order-details { background-color: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Sultan Library</h1>
                </div>
                <div class='content'>
                    <h2>Merci pour votre commande !</h2>
                    <p>Votre commande #{$orderNumber} a été confirmée et sera traitée sous peu.</p>
                    <div class='order-details'>
                        <h3>Détails de la commande</h3>
                        <p><strong>Total:</strong> " . formatPrice($orderDetails['total']) . "</p>
                        <p><strong>Statut:</strong> {$orderDetails['status']}</p>
                    </div>
                    <p>Vous pouvez suivre votre commande depuis votre compte.</p>
                </div>
                <div class='footer'>
                    <p>Sultan Library - Livres et Objets Culturels Islamiques</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return self::send($userEmail, $subject, $message);
    }
    
    /**
     * Send password reset email
     */
    public static function sendPasswordReset($userEmail, $resetToken) {
        $resetUrl = BASE_URL . 'auth/reset-password?token=' . $resetToken;
        $subject = "Réinitialisation de votre mot de passe - Sultan Library";
        
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .button { display: inline-block; padding: 12px 24px; background-color: #56474A; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Réinitialisation de mot de passe</h2>
                <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
                <p>Cliquez sur le lien ci-dessous pour créer un nouveau mot de passe :</p>
                <a href='{$resetUrl}' class='button'>Réinitialiser mon mot de passe</a>
                <p>Ce lien expirera dans 1 heure.</p>
                <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
            </div>
        </body>
        </html>
        ";
        
        return self::send($userEmail, $subject, $message);
    }
    
    /**
     * Send generic email
     */
    private static function send($to, $subject, $message) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Sultan Library <noreply@sultanlibrary.com>" . "\r\n";
        
        // In production, use a proper email service (SMTP, SendGrid, etc.)
        // For now, just log it in development
        if (APP_DEBUG) {
            error_log("Email to {$to}: {$subject}");
            return true;
        }
        
        return mail($to, $subject, $message, $headers);
    }
}

