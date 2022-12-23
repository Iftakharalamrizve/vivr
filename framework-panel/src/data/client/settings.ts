import { Settings , SettingsOptions } from '@types';
import { API_ENDPOINTS } from './api-endpoints';
import { crudFactory } from './curd-factory';
import { HttpClient } from '@/data/client/http-client';

export const settingsClient = {
  ...crudFactory<Settings, any, SettingsOptions>(API_ENDPOINTS.SETTINGS),
  all({ language }: { language: string }) {
    return HttpClient.get<Settings>(API_ENDPOINTS.SETTINGS, {
      language,
    });
  },
  update: ({ ...data }: Settings) => {
    return HttpClient.post<Settings>(API_ENDPOINTS.SETTINGS, { ...data });
  },
};
