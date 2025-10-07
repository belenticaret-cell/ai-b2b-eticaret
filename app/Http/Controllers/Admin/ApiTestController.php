<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RealApiConfigService;
use App\Models\Magaza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiTestController extends Controller
{
    private RealApiConfigService $apiConfigService;

    public function __construct(RealApiConfigService $apiConfigService)
    {
        $this->apiConfigService = $apiConfigService;
    }

    public function index()
    {
        $apiMode = $this->apiConfigService->isRealApiMode() ? 'Real API' : 'Mock Mode';
        $magazalar = Magaza::all();
        
        return view('admin.api-test.index', compact('apiMode', 'magazalar'));
    }

    public function testCredentials(Request $request)
    {
        $request->validate([
            'platform' => 'required|string',
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'supplier_id' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'merchant_id' => 'nullable|string',
        ]);

        $platform = $request->input('platform');
        $credentials = $request->only(['api_key', 'api_secret', 'supplier_id', 'username', 'password', 'merchant_id']);
        
        // Boş değerleri filtrele
        $credentials = array_filter($credentials, function($value) {
            return !empty($value);
        });

        Log::info('API credentials test başlatıldı', [
            'platform' => $platform,
            'credentials_keys' => array_keys($credentials),
            'user_id' => auth()->id()
        ]);

        $result = $this->apiConfigService->validateCredentials($platform, $credentials);

        Log::info('API credentials test tamamlandı', [
            'platform' => $platform,
            'valid' => $result['valid'],
            'result' => $result
        ]);

        if ($result['valid']) {
            return back()->with('success', "✅ {$platform} API: " . $result['message']);
        } else {
            return back()->with('error', "❌ {$platform} API: " . $result['message']);
        }
    }

    public function toggleApiMode(Request $request)
    {
        $newMode = $request->input('mock_mode', true);
        
        // .env dosyasını güncelle (basit yöntem)
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);
        
        if (strpos($envContent, 'MOCK_API_MODE=') !== false) {
            $envContent = preg_replace('/MOCK_API_MODE=.*/m', 'MOCK_API_MODE=' . ($newMode ? 'true' : 'false'), $envContent);
        } else {
            $envContent .= "\nMOCK_API_MODE=" . ($newMode ? 'true' : 'false');
        }
        
        file_put_contents($envFile, $envContent);

        $mode = $newMode ? 'Mock Mode' : 'Real API Mode';
        return back()->with('success', "🔄 API Mode değiştirildi: {$mode}");
    }

    public function envCredentials()
    {
        $platforms = ['trendyol', 'hepsiburada', 'n11'];
        $envCredentials = [];
        
        foreach ($platforms as $platform) {
            $credentials = $this->apiConfigService->getCredentialsFromEnv($platform);
            $envCredentials[$platform] = [
                'credentials' => $credentials,
                'filled' => !empty(array_filter($credentials))
            ];
        }
        
        return view('admin.api-test.env-credentials', compact('envCredentials'));
    }

    public function bulkTestMagazalar()
    {
        $magazalar = Magaza::where('aktif', true)->get();
        $results = [];
        
        foreach ($magazalar as $magaza) {
            $credentials = [
                'api_key' => $magaza->api_anahtari,
                'api_secret' => $magaza->api_gizli_anahtari,
                'supplier_id' => $magaza->magaza_id,
                'username' => $magaza->api_anahtari, // HB için
                'password' => $magaza->api_gizli_anahtari, // HB için
                'merchant_id' => $magaza->magaza_id, // HB için
            ];
            
            $result = $this->apiConfigService->validateCredentials($magaza->platform, $credentials);
            
            $results[] = [
                'magaza' => $magaza,
                'result' => $result
            ];
        }
        
        return view('admin.api-test.bulk-results', compact('results'));
    }
}