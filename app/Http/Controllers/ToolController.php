<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index()
    {
        return response()->json(
            Tool::latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'array'],
            'title.ar' => ['nullable', 'string'],
            'title.en' => ['nullable', 'string'],

            'description' => ['required', 'array'],
            'description.ar' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
        ]);

        $tool = Tool::create($validated);

        return response()->json([
            'message' => 'Tool created successfully.',
            'tool' => $tool,
        ], 201);
    }

    public function show(Tool $tool)
    {
        return response()->json($tool);
    }

    public function update(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'title' => ['required', 'array'],
            'title.ar' => ['nullable', 'string'],
            'title.en' => ['nullable', 'string'],

            'description' => ['required', 'array'],
            'description.ar' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
        ]);

        $tool->update($validated);

        return response()->json([
            'message' => 'Tool updated successfully.',
            'tool' => $tool->fresh(),
        ]);
    }

    public function destroy(Tool $tool)
    {
        $tool->delete();

        return response()->json([
            'message' => 'Tool deleted successfully.',
        ]);
    }

    public function checkUrl(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'string', 'max:2048'],
        ]);

        $url = trim($validated['url']);
        $warnings = [];
        $riskScore = 0;

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json([
                'risk' => 'high',
                'color' => 'danger',
                'warningKeys' => ['invalid_url'],
            ]);
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if (($parts['scheme'] ?? '') !== 'https') {
            $warnings[] = 'warning_https';
            $riskScore += 2;
        }

        if (preg_match('/bit\.ly|tinyurl\.com|t\.co|goo\.gl|ow\.ly/i', $host)) {
            $warnings[] = 'warning_short';
            $riskScore += 3;
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $warnings[] = 'warning_ip';
            $riskScore += 3;
        }

        if (str_contains($url, '@')) {
            $warnings[] = 'warning_at_symbol';
            $riskScore += 3;
        }

        if (strlen($host) > 40) {
            $warnings[] = 'warning_long_domain';
            $riskScore += 1;
        }

        if (substr_count($host, '.') >= 4) {
            $warnings[] = 'warning_subdomains';
            $riskScore += 2;
        }

        $suspiciousWords = [
            'login',
            'verify',
            'verification',
            'account',
            'secure',
            'security',
            'update',
            'password',
            'signin',
            'confirm',
            'bank',
            'wallet',
            'payment',
        ];

        foreach ($suspiciousWords as $word) {
            if (str_contains(strtolower($url), $word)) {
                $warnings[] = 'warning_sensitive_keyword';
                $riskScore += 1;
                break;
            }
        }

        if ($riskScore >= 5) {
            $risk = 'high';
            $color = 'danger';
        } elseif ($riskScore >= 2) {
            $risk = 'medium';
            $color = 'warn';
        } else {
            $risk = 'low';
            $color = 'safe';
        }

        if (empty($warnings)) {
            $warnings[] = 'safe_note';
        }

        return response()->json([
            'risk' => $risk,
            'color' => $color,
            'warningKeys' => $warnings,
        ]);
    }
}