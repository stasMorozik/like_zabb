import { Either } from "@sweet-monads/either";
import { Subject, Observable } from 'rxjs';
import { RegistrationUseCase } from '../use-cases/registration';
import { notAuthFetch } from './shared/not-auth.fetch';

export namespace RegistrationAdapters {
  export class Emiter implements RegistrationUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error, boolean>>
    ){}

    emit(either: Either<Error, boolean>): void {
      this._subject.next(either)
    }
  }

  export class Api implements RegistrationUseCase.Ports.Api {
    fetch(dto: RegistrationUseCase.Dtos.Data): Observable<Either<Error, boolean>> {
      return notAuthFetch<boolean>({
        url: '/api/accounts/',
        method: 'POST',
        body: dto
      })
    }
  }
}
