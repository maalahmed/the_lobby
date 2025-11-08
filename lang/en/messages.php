<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Response Messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in API responses throughout
    | the application. These messages provide feedback to API consumers.
    |
    */

    // General messages
    'success' => 'Operation completed successfully',
    'error' => 'An error occurred',
    'not_found' => 'Resource not found',
    'validation_error' => 'Validation error',
    'unauthorized' => 'Unauthorized access',
    'forbidden' => 'You do not have permission to perform this action',
    'server_error' => 'Internal server error',

    // Properties
    'properties_retrieved' => 'Properties retrieved successfully',
    'property_created' => 'Property created successfully',
    'property_updated' => 'Property updated successfully',
    'property_deleted' => 'Property deleted successfully',
    'property_not_found' => 'Property not found',

    // Units
    'units_retrieved' => 'Units retrieved successfully',
    'unit_created' => 'Unit created successfully',
    'unit_updated' => 'Unit updated successfully',
    'unit_deleted' => 'Unit deleted successfully',
    'unit_not_found' => 'Unit not found',

    // Tenants
    'tenants_retrieved' => 'Tenants retrieved successfully',
    'tenant_created' => 'Tenant created successfully',
    'tenant_updated' => 'Tenant updated successfully',
    'tenant_deleted' => 'Tenant deleted successfully',
    'tenant_not_found' => 'Tenant not found',

    // Contracts
    'contracts_retrieved' => 'Contracts retrieved successfully',
    'contract_created' => 'Contract created successfully',
    'contract_updated' => 'Contract updated successfully',
    'contract_deleted' => 'Contract deleted successfully',
    'contract_signed' => 'Contract signed successfully',
    'contract_not_found' => 'Contract not found',

    // Invoices
    'invoices_retrieved' => 'Invoices retrieved successfully',
    'invoice_created' => 'Invoice created successfully',
    'invoice_updated' => 'Invoice updated successfully',
    'invoice_deleted' => 'Invoice deleted successfully',
    'invoice_sent' => 'Invoice sent successfully',
    'invoice_not_found' => 'Invoice not found',

    // Payments
    'payments_retrieved' => 'Payments retrieved successfully',
    'payment_created' => 'Payment created successfully',
    'payment_updated' => 'Payment updated successfully',
    'payment_deleted' => 'Payment deleted successfully',
    'payment_verified' => 'Payment verified successfully',
    'payment_not_found' => 'Payment not found',

    // Maintenance
    'maintenance_requests_retrieved' => 'Maintenance requests retrieved successfully',
    'maintenance_request_created' => 'Maintenance request created successfully',
    'maintenance_request_updated' => 'Maintenance request updated successfully',
    'maintenance_request_deleted' => 'Maintenance request deleted successfully',
    'maintenance_request_assigned' => 'Maintenance request assigned successfully',
    'maintenance_request_completed' => 'Maintenance request completed successfully',
    'maintenance_request_not_found' => 'Maintenance request not found',

    // Maintenance Jobs
    'maintenance_jobs_retrieved' => 'Maintenance jobs retrieved successfully',
    'maintenance_job_created' => 'Maintenance job created successfully',
    'maintenance_job_updated' => 'Maintenance job updated successfully',
    'maintenance_job_deleted' => 'Maintenance job deleted successfully',
    'maintenance_job_accepted' => 'Maintenance job accepted successfully',
    'maintenance_job_rejected' => 'Maintenance job rejected successfully',
    'maintenance_job_started' => 'Maintenance job started successfully',
    'maintenance_job_completed' => 'Maintenance job completed successfully',
    'maintenance_job_not_found' => 'Maintenance job not found',

    // Service Providers
    'service_providers_retrieved' => 'Service providers retrieved successfully',
    'service_provider_created' => 'Service provider created successfully',
    'service_provider_updated' => 'Service provider updated successfully',
    'service_provider_deleted' => 'Service provider deleted successfully',
    'service_provider_not_found' => 'Service provider not found',

    // Notifications
    'notifications_retrieved' => 'Notifications retrieved successfully',
    'notification_marked_read' => 'Notification marked as read',
    'all_notifications_marked_read' => 'All notifications marked as read',
    'notification_not_found' => 'Notification not found',

    // Messages
    'messages_retrieved' => 'Messages retrieved successfully',
    'message_sent' => 'Message sent successfully',
    'message_updated' => 'Message updated successfully',
    'message_deleted' => 'Message deleted successfully',
    'message_marked_read' => 'Message marked as read',
    'message_not_found' => 'Message not found',

    // Validation and Business Rules
    'unit_has_active_contracts' => 'Cannot delete unit with active lease contracts',
    'tenant_has_active_contracts' => 'Cannot delete tenant with active lease contracts',
    'only_draft_contracts_can_be_deleted' => 'Only draft contracts can be deleted',
    'only_draft_invoices_can_be_deleted' => 'Only draft invoices can be deleted',
    'completed_payments_cannot_be_deleted' => 'Completed payments cannot be deleted for audit purposes',
    'cannot_delete_request_with_jobs' => 'Cannot delete maintenance request with assigned jobs',
    'cannot_delete_active_jobs' => 'Cannot delete jobs that are in progress or completed',
    'cannot_delete_provider_with_active_jobs' => 'Cannot delete service provider with active jobs',
    'notification_deleted' => 'Notification deleted successfully',

];
