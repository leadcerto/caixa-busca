<?php

namespace App\Console\Commands;

use App\Models\Bairro;
use App\Models\Imovel;
use Illuminate\Console\Command;

class LimparBairros extends Command
{
    protected $signature = 'bairros:limpar {--preview : Mostra as mudanças sem aplicar ao banco}';
    protected $description = 'Normaliza nomes e remove duplicatas de bairros';

    public function handle(): int
    {
        $preview = $this->option('preview');
        $updatedNames  = 0;
        $deletedDupes  = 0;
        $movedImoveis  = 0;

        // Passo 1: normalizar nomes
        $bairros = Bairro::orderBy('id_municipio')->orderBy('id')->get();

        foreach ($bairros as $bairro) {
            $normalized = $this->normalize($bairro->nome);
            if ($normalized !== $bairro->nome) {
                $this->line("RENAME [{$bairro->id_municipio}|{$bairro->id}]: \"{$bairro->nome}\" → \"{$normalized}\"");
                if (!$preview) {
                    $bairro->nome = $normalized;
                    $bairro->save();
                }
                $updatedNames++;
            }
        }

        // Passo 2: recarregar (após renomear) e agrupar por nome normalizado + municipio
        $bairros = Bairro::orderBy('id_municipio')->orderBy('id')->get();

        $grouped = [];
        foreach ($bairros as $bairro) {
            $key = $bairro->id_municipio . '||' . $this->normalize($bairro->nome);
            $grouped[$key][] = $bairro;
        }

        // Passo 3: mesclar duplicatas
        foreach ($grouped as $grupo) {
            if (count($grupo) <= 1) {
                continue;
            }

            // Conta imóveis para cada bairro e ordena decrescente
            $ranked = collect($grupo)
                ->map(fn ($b) => ['bairro' => $b, 'count' => Imovel::where('id_bairro', $b->id)->count()])
                ->sortByDesc('count')
                ->values();

            $canonical = $ranked->first()['bairro'];

            foreach ($ranked->skip(1) as $item) {
                $dup = $item['bairro'];
                $cnt = $item['count'];

                $this->line(
                    "MERGE [mun {$dup->id_municipio}]: ID {$dup->id} \"{$dup->nome}\" ({$cnt} imóveis)" .
                    " → ID {$canonical->id} \"{$canonical->nome}\""
                );

                if (!$preview) {
                    if ($cnt > 0) {
                        Imovel::where('id_bairro', $dup->id)->update(['id_bairro' => $canonical->id]);
                    }
                    // Remove sub-bairros órfãos antes de deletar
                    $dup->subBairros()->delete();
                    $dup->delete();
                }

                $deletedDupes++;
                $movedImoveis += $cnt;
            }
        }

        $mode = $preview ? '[PRÉVIA — nenhuma alteração feita]' : '[EXECUTADO]';
        $this->info("{$mode}");
        $this->info("Nomes normalizados : {$updatedNames}");
        $this->info("Duplicatas removidas: {$deletedDupes}");
        $this->info("Imóveis remapeados : {$movedImoveis}");

        return 0;
    }

    private function normalize(string $nome): string
    {
        $nome = mb_strtoupper(trim($nome), 'UTF-8');

        // Remove acentos
        $nome = preg_replace('/[ÁÀÃÂÄ]/u', 'A', $nome);
        $nome = preg_replace('/[ÉÈÊË]/u',  'E', $nome);
        $nome = preg_replace('/[ÍÌÎÏ]/u',  'I', $nome);
        $nome = preg_replace('/[ÓÒÕÔÖ]/u', 'O', $nome);
        $nome = preg_replace('/[ÚÙÛÜ]/u',  'U', $nome);
        $nome = preg_replace('/Ç/u',       'C', $nome);
        $nome = preg_replace('/Ñ/u',       'N', $nome);

        // Expande abreviações
        $nome = preg_replace('/^FREG\.?\s+/u', 'FREGUESIA ', $nome);
        $nome = preg_replace('/\bFREG\.\s*/u', 'FREGUESIA ', $nome);

        // "NOME (CONTEUDO)" → "NOME CONTEUDO"
        $nome = preg_replace('/\s*\(([^)]+)\)/u', ' $1', $nome);

        // Remove pontos e normaliza espaços
        $nome = str_replace('.', '', $nome);
        $nome = trim(preg_replace('/\s+/', ' ', $nome));

        return $nome;
    }
}
