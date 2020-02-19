<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->exclude('bootstrap/cache')
    ->exclude('node_modules')
    ->exclude('resources/assets')
    ->exclude('resources/views')
    ->exclude('routes')
    ->exclude('storage')
    ->exclude('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('_ide_helper.php')
    ->notName('_ide_helper_models.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'single_space', 'operators' => ['=>' => 'align_single_space_minimal']],
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => ['statements' => ['return', 'throw']],
        'braces' => ['allow_single_line_closure' => true],
        'cast_spaces' => true,
        'class_attributes_separation' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => true,
        'declare_strict_types' => true, // risky
        'ereg_to_preg' => true, // risky
        'escape_implicit_backslashes' => true,
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => false,
        'fully_qualified_strict_types' => true,
        'function_typehint_space' => true,
        'include' => true,
        'increment_style' => true,
        'is_null' => true, // risky
        'linebreak_after_opening_tag' => true,
        'logical_operators' => true, // risky
        'lowercase_cast' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'method_chaining_indentation' => true,
        'modernize_types_casting' => true, // risky
        'multiline_comment_opening_closing' => true,
        'multiline_whitespace_before_semicolons' => true,
        'native_constant_invocation' => true, // risky
        'native_function_casing' => true,
        'native_function_invocation' => ['scope' => 'namespaced', 'strict' => true], // risky
        'new_with_braces' => true,
        'no_alternative_syntax' => true,
        'no_binary_string' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => false,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => true,
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_null_property_initialization' => false,
        'no_short_bool_cast' => true,
        'no_short_echo_tag' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_around_offset' => true,
        'no_superfluous_elseif' => true,
        'no_superfluous_phpdoc_tags' => false,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_curly_braces' => true,
        'no_unneeded_final_method' => true,
        'no_unset_cast' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'normalize_index_brace' => true,
        'not_operator_with_successor_space' => true,
        'object_operator_without_whitespace' => true,
        'ordered_class_elements' => false,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_alias_tag' => false,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => false,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name' => true,
        'pow_to_exponentiation' => true, // risky
        'protected_to_private' => true,
        'random_api_migration' => true, // risky
        'return_type_declaration' => ['space_before' => 'one'],
        'self_accessor' => false, // risky
        'semicolon_after_instruction' => true,
        'short_scalar_cast' => true,
        'simplified_null_return' => true,
        'single_blank_line_before_namespace' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_quote' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
        'standardize_increment' => true,
        'standardize_not_equals' => true,
        'strict_comparison' => true, // risky
        'strict_param' => true, // risky
        'string_line_ending' => true, // risky
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline_array' => false,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => false,
        'void_return' => true, // risky
        'whitespace_after_comma_in_array' => true,
        'yoda_style' => false
    ])
    ->setFinder($finder);
