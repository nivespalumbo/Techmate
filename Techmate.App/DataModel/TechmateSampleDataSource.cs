using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Threading.Tasks;
using Windows.Data.Json;
using Windows.Storage;

// Il modello dati definito da questo file è un esempio rappresentativo di un modello
// predefinito.  I nomi di proprietà scelti coincidono con le associazioni dati nei modelli di elemento standard.
//
// Le applicazioni possono utilizzare questo modello come punto di partenza ed elaborarlo, oppure scartarlo completamente e
// sostituirlo con uno adeguato alle esigenze. Se si utilizza questo modello, è possibile migliorare 
// la risposta dell'applicazione avviando l'attività di caricamento dati nel code-behind di App.xaml al primo avvio 
// dell'applicazione.

namespace Techmate.DataModel
{
    /// <summary>
    /// Crea una raccolta di gruppi ed elementi con contenuto letto da un file json statico.
    /// 
    /// SampleDataSource viene inizializzato con dati letti da un file json statico incluso nel 
    /// progetto.  In tal modo vengono forniti dati di esempio sia in fase di progettazione che di esecuzione.
    /// </summary>
    public sealed class TechmateSampleDataSource
    {
        private static TechmateSampleDataSource _sampleDataSource = new TechmateSampleDataSource();

        private ObservableCollection<Magazine> _magazines = new ObservableCollection<Magazine>();
        public ObservableCollection<Magazine> Magazines
        {
            get { return _magazines; }
        }

        public static async Task<IEnumerable<Magazine>> GetGroupsAsync()
        {
            await _sampleDataSource.GetSampleDataAsync();

            return _sampleDataSource.Magazines;
        }

        public static async Task<Magazine> GetGroupAsync(string uniqueId)
        {
            await _sampleDataSource.GetSampleDataAsync();
            // La semplice ricerca lineare è accettabile per piccoli set di dati
            var matches = _sampleDataSource.Magazines.Where((group) => group.Number.Equals(uniqueId));
            if (matches.Count() == 1) return matches.First();
            return null;
        }

        public static async Task<Article> GetItemAsync(string uniqueId)
        {
            await _sampleDataSource.GetSampleDataAsync();
            // La semplice ricerca lineare è accettabile per piccoli set di dati
            var matches = _sampleDataSource.Magazines.SelectMany(group => group.Articles).Where((item) => item.Number.Equals(uniqueId));
            if (matches.Count() == 1) return matches.First();
            return null;
        }

        private async Task GetSampleDataAsync()
        {
            if (_magazines.Count != 0)
                return;

            Uri dataUri = new Uri("ms-appx:///DataModel/TechmateSample.json");

            StorageFile file = await StorageFile.GetFileFromApplicationUriAsync(dataUri);
            string jsonText = await FileIO.ReadTextAsync(file);
            JsonObject jsonObject = JsonObject.Parse(jsonText);
            JsonArray jsonArray = jsonObject["Magazines"].GetArray();

            foreach (JsonValue groupValue in jsonArray)
            {
                JsonObject groupObject = groupValue.GetObject();
                Magazine group = new Magazine((int)groupObject["Number"].GetNumber(),
                                                            groupObject["Cover"].GetString(),
                                                            groupObject["Color"].GetString(),
                                                            groupObject["Abstract"].GetString(),
                                                            groupObject["PublishDate"].GetString(),
                                                            groupObject["Content"].GetString());

                foreach (JsonValue itemValue in groupObject["Articles"].GetArray())
                {
                    JsonObject itemObject = itemValue.GetObject();
                    group.Articles.Add(new Article(itemObject["Number"].GetString(),
                                                       itemObject["Author"].GetString(),
                                                       itemObject["Link"].GetString(),
                                                       itemObject["Section"].GetString(),
                                                       itemObject["Magazine"].GetString(),
                                                       itemObject["Title"].GetString(),
                                                       itemObject["Subtitle"].GetString(),
                                                       itemObject["Text"].GetString(),
                                                       null,
                                                       null));
                }
                Magazines.Add(group);
            }
        }
    }
}