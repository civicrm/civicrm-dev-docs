# hook_civicrm_alterMailContent

## Description

This hook is called after getting the content of the mail and before
tokenizing it.

## Definition

    hook_civicrm_alterMailContent(&$content)

## Parameters

-   $content - fields that include the content of the mail

## Details

-   $content - fields include: html, text, subject