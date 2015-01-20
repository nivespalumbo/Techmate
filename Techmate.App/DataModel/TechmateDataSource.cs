using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Diagnostics;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using Windows.Data.Json;
using Windows.Storage;
using Newtonsoft.Json;

namespace Techmate.DataModel
{
    public class TechmateDataSource
    {
        private static TechmateDataSource _dataSource = new TechmateDataSource();

        private ObservableCollection<Magazine> _magazines = new ObservableCollection<Magazine>();
        public ObservableCollection<Magazine> Magazines
        {
            get { return _magazines; }
        }

        public static async Task<IEnumerable<Magazine>> GetMagazinesAsync()
        {
            await _dataSource.GetMagazineListAsync();

            return _dataSource.Magazines;
        }

        public static async Task<Magazine> GetMagazineAsync(int uniqueId)
        {
            await _dataSource.GetMagazineListAsync();

            // La semplice ricerca lineare è accettabile per piccoli set di dati
            var matches = _dataSource.Magazines.Where((group) => group.Number == uniqueId);
            if (matches.Count() == 1) return matches.First();
            return null;
        }

        public static async Task<IEnumerable<Article>> GetArticlesAsync(int uniqueId)
        {
            await _dataSource.GetArticlesOfMagazine(uniqueId);

            var mag = _dataSource.Magazines.First(m => m.Number == uniqueId);
            if (mag != null) return mag.Articles;
            return null;
        }

        public static async Task<Article> GetArticleAsync(int magazineUniqueId, int articleUniqueId)
        {
            await _dataSource.GetArticlesOfMagazine(magazineUniqueId);

            // La semplice ricerca lineare è accettabile per piccoli set di dati
            var matches = _dataSource.Magazines.SelectMany(group => group.Articles).Where((item) => item.Number == articleUniqueId);
            if (matches.Count() == 1) return matches.First();
            return null;
        }

        private async Task GetMagazineListAsync()
        {
            if (_magazines.Count != 0)
                return;

#if !DEBUG
            Uri dataUri = new Uri("http://127.0.0.1/Techmate/api/magazine");

            HttpClient httpClient = new HttpClient();
            string jsonText =
                await httpClient.GetStringAsync(dataUri);
#else
            Uri dataUri = new Uri("ms-appx:///DataModel/TechmateSample.json");

            StorageFile file = await StorageFile.GetFileFromApplicationUriAsync(dataUri);
            string jsonText = await FileIO.ReadTextAsync(file);
#endif

            _magazines = JsonConvert.DeserializeObject<ObservableCollection<Magazine>>(jsonText);
        }

        private Task GetArticlesOfMagazine(int uniqueId)
        {
            throw new NotImplementedException("GetArticlesOfMagazine");
        }

//#else

//        private async Task GetMagazineListAsync()
//        {
//            if (_magazines.Count != 0)
//                return;

//            Uri dataUri = new Uri("ms-appx:///DataModel/TechmateSample.json");

//            StorageFile file = await StorageFile.GetFileFromApplicationUriAsync(dataUri);
//            string jsonText = await FileIO.ReadTextAsync(file);
//            JsonObject jsonObject = JsonObject.Parse(jsonText);
//            JsonArray jsonArray = jsonObject["Magazines"].GetArray();

//            foreach (JsonValue groupValue in jsonArray)
//            {
//                JsonObject groupObject = groupValue.GetObject();
//                Magazine group = new Magazine((int)groupObject["Number"].GetNumber(),
//                                                            groupObject["Cover"].GetString(),
//                                                            groupObject["Color"].GetString(),
//                                                            groupObject["Abstract"].GetString(),
//                                                            groupObject["PublishDate"].GetString(),
//                                                            groupObject["Content"].GetString());

                
//                foreach (JsonValue itemValue in groupObject["Articles"].GetArray())
//                {
//                    JsonObject itemObject = itemValue.GetObject();
//                    group.Articles.Add(new Article((int) itemObject["Number"].GetNumber(),
//                        itemObject["Author"].GetString(),
//                        itemObject["Link"].GetString(),
//                        itemObject["Section"].GetString(),
//                        itemObject["Title"].GetString(),
//                        itemObject["Subtitle"].GetString(),
//                        itemObject["Text"].GetString()));
//                }
                
//                Magazines.Add(group);
//            }
//        }

//        private async Task GetArticlesOfMagazine(int uniqueId)
//        {
//            var mag = await GetMagazineAsync(uniqueId);
//            if (mag == null)
//                return;

//            Uri dataUri = new Uri("ms-appx:///DataModel/TechmateSample.json");

//            StorageFile file = await StorageFile.GetFileFromApplicationUriAsync(dataUri);
//            string jsonText = await FileIO.ReadTextAsync(file);
//            JsonObject jsonObject = JsonObject.Parse(jsonText);
//            JsonArray jsonArray = jsonObject["Magazines"].GetArray();

//            foreach (JsonValue groupValue in jsonArray)
//            {
//                JsonObject groupObject = groupValue.GetObject();
//                Magazine group = new Magazine((int)groupObject["Number"].GetNumber(),
//                                                            groupObject["Cover"].GetString(),
//                                                            groupObject["Color"].GetString(),
//                                                            groupObject["Abstract"].GetString(),
//                                                            groupObject["PublishDate"].GetString(),
//                                                            groupObject["Content"].GetString());


//                foreach (JsonValue itemValue in groupObject["Articles"].GetArray())
//                {
//                    JsonObject itemObject = itemValue.GetObject();
//                    group.Articles.Add(new Article((int)itemObject["Number"].GetNumber(),
//                        itemObject["Author"].GetString(),
//                        itemObject["Link"].GetString(),
//                        itemObject["Section"].GetString(),
//                        itemObject["Title"].GetString(),
//                        itemObject["Subtitle"].GetString(),
//                        itemObject["Text"].GetString()));
//                }

//                Magazines.Add(group);
//            }
//        }

    }
}
