# Laravel Chatbot Support Module -- Migration Implementation Guide

This document bundles the **complete chatbot migration module** for a
Laravel project. It includes migrations, models, services, controllers,
views, routes, and installation steps.

------------------------------------------------------------------------

# 1. Overview

This module adds a **Customer Support Chatbot System** to a Laravel
commerce platform.

Features:

-   Chatbot widget for customers
-   FAQ auto‑responses
-   Order tracking responses
-   Human support escalation
-   Admin conversation dashboard
-   Support tickets with SLA tracking
-   Attachments and voice notes
-   Conversation summaries
-   Macros for admin replies
-   Analytics dashboard
-   WhatsApp and SMS webhook structure
-   Email notifications
-   Broadcast-ready messaging events

------------------------------------------------------------------------

# 2. Folder Structure

    app/
    ├── Events/
    │   └── ChatbotMessageSent.php
    ├── Http/Controllers/
    │   ├── Chatbot/ChatbotController.php
    │   ├── Account/SupportCenterController.php
    │   ├── Admin/
    │   │   ├── ChatbotController.php
    │   │   ├── SupportTicketController.php
    │   │   ├── ChatbotFaqController.php
    │   │   ├── ChatbotMacroController.php
    │   │   └── ChatbotAnalyticsController.php
    │   └── Webhook/
    │       ├── WhatsAppWebhookController.php
    │       └── SmsWebhookController.php
    ├── Mail/
    │   └── SupportTicketUpdatedMail.php
    ├── Models/
    │   ├── ChatbotConversation.php
    │   ├── ChatbotMessage.php
    │   ├── ChatbotFaq.php
    │   ├── ChatbotHandoff.php
    │   ├── ChatbotMacro.php
    │   ├── ChatbotSummary.php
    │   ├── ChatbotTypingState.php
    │   └── SupportTicket.php
    └── Services/
        ├── FileUploadService.php
        └── Chatbot/
            ├── IntentClassifier.php
            ├── FaqMatcher.php
            ├── OrderReferenceParser.php
            ├── SentimentAnalyzer.php
            ├── PriorityScorer.php
            ├── TicketNumberService.php
            ├── ResponseEngine.php
            ├── SlaService.php
            ├── ConversationSummaryService.php
            ├── QualityScoringService.php
            ├── AdminReplySuggestionService.php
            └── AdminAlertService.php

    database/
    ├── migrations/
    │   ├── create_chatbot_conversations_table
    │   ├── create_chatbot_messages_table
    │   ├── create_chatbot_faqs_table
    │   ├── create_chatbot_handoffs_table
    │   ├── create_support_tickets_table
    │   ├── create_chatbot_macros_table
    │   ├── create_chatbot_summaries_table
    │   └── create_chatbot_typing_states_table
    └── seeders/
        └── ChatbotFaqSeeder.php

    resources/views/
    ├── components/chatbot-widget.blade.php
    ├── account/support/
    │   ├── index.blade.php
    │   ├── conversation.blade.php
    │   └── ticket.blade.php
    ├── admin/chatbot/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── admin/support_tickets/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── admin/chatbot_faqs/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── admin/chatbot_macros/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    ├── admin/chatbot_analytics/
    │   └── index.blade.php
    └── emails/
        └── support_ticket_updated.blade.php

------------------------------------------------------------------------

# 3. Installation Steps

## Step 1 --- Copy Files

Copy all module files into your Laravel project following the folder
structure above.

------------------------------------------------------------------------

## Step 2 --- Update Routes

Add chatbot routes to `routes/web.php`:

    Route::prefix('chatbot')->group(function () {
        Route::post('/start', ChatbotController::class.'@start');
        Route::post('/send', ChatbotController::class.'@send');
    });

Add admin chatbot routes and support center routes as described in the
implementation guide.

------------------------------------------------------------------------

## Step 3 --- Update Layout

Add the chatbot widget to your main layout:

    @include('components.chatbot-widget')

Place it before the closing `</body>` tag.

------------------------------------------------------------------------

## Step 4 --- Environment Configuration

Add the following to `.env`:

    CHATBOT_AI_ENABLED=false
    CHATBOT_AI_PROVIDER=
    CHATBOT_AI_API_KEY=

    WHATSAPP_ENABLED=false
    WHATSAPP_PROVIDER=
    WHATSAPP_TOKEN=

    SMS_ENABLED=false
    SMS_PROVIDER=
    SMS_TOKEN=

------------------------------------------------------------------------

# 4. Run Laravel Commands

After copying files run:

    php artisan migrate
    php artisan db:seed
    php artisan storage:link
    php artisan optimize:clear

If using queued mail:

    php artisan queue:work

Optional real‑time messaging:

    composer require laravel/reverb
    php artisan reverb:install
    php artisan reverb:start

------------------------------------------------------------------------

# 5. Basic Testing Checklist

Verify these functions:

-   chatbot widget loads
-   conversation starts
-   bot replies appear
-   refund request triggers escalation
-   support ticket created
-   admin dashboard lists conversations
-   admin can reply
-   admin can resolve conversations
-   SLA tracking works
-   FAQs respond correctly
-   macros insert replies
-   analytics page loads

------------------------------------------------------------------------

# 6. Required Existing Tables

This module assumes the project already has:

-   users table
-   products table
-   orders table
-   notifications table
-   authentication system

------------------------------------------------------------------------

# 7. Troubleshooting

Common fixes:

**Autoload errors**

    composer dump-autoload

**Storage files not loading**

    php artisan storage:link

**Migration errors**

    php artisan migrate:fresh

------------------------------------------------------------------------

# 8. Recommended Deployment Phases

### Phase 1

Core chatbot and widget.

### Phase 2

Admin dashboard and tickets.

### Phase 3

Analytics, macros, and webhooks.

------------------------------------------------------------------------

# End of Guide
