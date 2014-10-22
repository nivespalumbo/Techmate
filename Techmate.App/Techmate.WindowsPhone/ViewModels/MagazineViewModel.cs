using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Diagnostics;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Animation;

namespace Techmate.WindowsPhone.ViewModels
{
    public class MagazineViewModel : INotifyPropertyChanged
    {
        private int _number;
        private string _cover;
        private string _color;
        private bool _published = true;
        private string _publishDate;
        private Dictionary<string, string> _abstract;
        private Dictionary<string, string> _content;

        public int Number
        {
            get { return _number; }
            set 
            {
                if (_number != value)
                {
                    _number = value;
                    NotifyPropertyChanged("Number");
                }
            }
        }
        public string Cover
        {
            get { return _cover; }
            set
            {
                if (!_cover.Equals(value))
                {
                    _cover = value;
                    NotifyPropertyChanged("Cover");
                }
            }
        }
        public string Color
        {
            get { return _color; }
            set 
            {
                if (!_color.Equals(value))
                {
                    _color = value;
                    NotifyPropertyChanged("Color");
                }
            }
        }
        public string PublishDate
        {
            get { return _publishDate; }
            set 
            {
                if (!_publishDate.Equals(value))
                {
                    _publishDate = value;
                    NotifyPropertyChanged("PublishDate");
                }
            }
        }
        public Dictionary<string, string> Abstract
        {
            get { return _abstract; }
            set 
            {
                if (!_abstract.Equals(value))
                {
                    _abstract = value;
                    NotifyPropertyChanged("Abstract");
                }
            }
        }
        public Dictionary<string, string> Content
        {
            get { return _content; }
            set
            {
                if (!_content.Equals(value))
                {
                    _content = value;
                    NotifyPropertyChanged("Content");
                }
            }
        }

        public event PropertyChangedEventHandler PropertyChanged;
        private void NotifyPropertyChanged(String propertyName)
        {
            PropertyChangedEventHandler handler = PropertyChanged;
            if (null != handler)
            {
                handler(this, new PropertyChangedEventArgs(propertyName));
            }
        }
    }
}